<?php

namespace App\Console\Commands;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Models\Ghg_sheet;
use App\Models\Ghg_sheet_line;
use App\Models\Ghg_tmpl;
use App\Utils\Constant;
use App\View\Components\Formula\All_SlugifyByName;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloneTemplateForGhgSheetCommand extends Command
{
    // use CloneRunTrait;
    use TraitEntityFormula;

    protected $type;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:cloneGhg 
    {--ownerId= : ID of current user}
    {--month= : Month of the sheet}
    {--tmplId= : ID of the template}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new GHG Sheet by cloning a template.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ownerId = $this->input->getOption('ownerId');
        $tmplId = $this->input->getOption('tmplId');
        $month = $this->input->getOption('month');
        $tmplItem = Ghg_tmpl::findOrFail($tmplId);
        if (!$ownerId) {
            $this->info("Owner ID: {$ownerId} doesn't exist");
            return Command::FAILURE;
        }
        if (!$tmplItem) {
            $this->info("GHG template ID: {$tmplId} doesn't exist");
            return Command::FAILURE;
        }
        if (!$month) {
            $this->info("Month param is missing");
            return Command::FAILURE;
        }
        try {
            // $valueDefaultAssignee1 = $this->getDefaultValues('assignee_1');
            $createdDoc = Ghg_sheet::create([
                'slug' => (new All_SlugifyByName())($tmplItem->slug, 'hse_insp_chklst_sht', ''),
                'ghg_tmpl_id' => $tmplItem->id,
                'owner_id' => $ownerId,
                'ghg_month' => $month,
                // 'assignee_1' => $valueDefaultAssignee1,
                'status' => 'new',
                'total' => 0,
            ]);
            foreach ($tmplItem->getLines as $tmplLine) {
                Ghg_sheet_line::create([
                    'ghg_sheet_id' => $createdDoc->id,
                    'ghg_metric_type_1_id' => $tmplLine->ghg_metric_type_1_id,
                    'ghg_metric_type_2_id' => $tmplLine->ghg_metric_type_2_id,
                    'factor' => $tmplLine->factor,
                    'value' => $tmplLine->value,
                    'unit' => $tmplLine->unit,
                    'owner_id' => $ownerId,
                ]);
            }
            // <<This id will be use to redirect, please don't add more text into it.
            $this->info($createdDoc->id);
            return Command::SUCCESS;
        } catch (\Throwable $th) {

            $this->error($th->getMessage());
            // $this->error($th->getMessage());
            return Command::FAILURE;
        }
    }
    // private function getDefaultValues($column)
    // {
    //     $props = SuperProps::getFor('hse_insp_chklst_sht')['props'] ?? [];
    //     $defaultValue = $props['_' . $column]['default-values']['default_value'];
    //     return $defaultValue ? $defaultValue : null;
    // }
}
