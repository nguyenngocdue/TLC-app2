<?php

namespace App\Console\Commands;

use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Models\Hse_insp_chklst;
use App\Models\Hse_insp_chklst_line;
use App\Models\Hse_insp_chklst_sht;
use App\Models\Hse_insp_tmpl;
use App\Models\Hse_insp_tmpl_sht;
use App\Models\Prod_order;
use App\Models\Qaqc_insp_chklst_line;
use App\View\Components\Formula\All_SlugifyByName;
use App\View\Components\Formula\TSO_GetAssignee1;
use Illuminate\Console\Command;

class CloneTemplateHseCommand extends Command
{
    // use CloneRunTrait;
    use TraitEntityFormula;

    protected $type;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:cloneHse 
    {--ownerId= : ID of current user}
    {--inspTmplId= : ID template Hse_insp_tmpls}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Checklist HSE by cloning a template, all subsequence Sheets and Lines';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ownerId = $this->input->getOption('ownerId');
        $inspTmplId = $this->input->getOption('inspTmplId');
        $hseInspTmplSht = Hse_insp_tmpl_sht::findOrFail($inspTmplId);
        if (!$ownerId) {
            $this->info("Owner ID:{$ownerId} doesn't exist");
            return Command::FAILURE;
        }
        if (!$hseInspTmplSht) {
            $this->info("Hse_insp_tmpl_sht ID:{$inspTmplId} doesn't exist");
            return Command::FAILURE;
        }
        try {
            $hseInspChklstSht = Hse_insp_chklst_sht::create([
                'name' => $hseInspTmplSht->name,
                'description' => $hseInspTmplSht->description,
                'slug' => (new All_SlugifyByName())($hseInspTmplSht->slug, 'hse_insp_chklst_sht', ''),
                'hse_insp_tmpl_sht_id' => $hseInspTmplSht->id,
                'owner_id' => $ownerId,
                'assignee_1' => (new TSO_GetAssignee1())($ownerId),
                'status' => 'new',
                'progress' => 0,
                'order_no' => $hseInspTmplSht->order_no,
            ]);
            foreach ($hseInspTmplSht->getLines as $hseInspTmplLine) {
                Hse_insp_chklst_line::create([
                    'name' => $hseInspTmplLine->name,
                    'description' => $hseInspTmplLine->description,
                    'control_type_id' => $hseInspTmplLine->control_type_id,
                    'hse_insp_group_id' => $hseInspTmplLine->hse_insp_group_id,
                    'hse_insp_control_group_id' => $hseInspTmplLine->hse_insp_control_group_id,
                    'hse_insp_chklst_sht_id' => $hseInspChklstSht->id,
                    'owner_id' => $ownerId,
                ]);
            }
            //<<This id will be use to redirect, please don't add more text into it.
            $this->info($hseInspChklstSht->id);
            return Command::SUCCESS;
        } catch (\Throwable $th) {

            $this->error($th->getMessage());
            // $this->error($th->getMessage());
            return Command::FAILURE;
        }
    }
}
