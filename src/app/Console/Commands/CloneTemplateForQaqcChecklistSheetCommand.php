<?php

namespace App\Console\Commands;

use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_sht;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_tmpl_sht;
use App\Utils\Support\Json\SuperProps;
use App\View\Components\Formula\All_SlugifyByName;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloneTemplateForQaqcChecklistSheetCommand extends Command
{
    // use CloneRunTrait;
    use TraitEntityFormula;

    protected $type = 'qaqc_insp_chklst_shts';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:cloneQaqcSheet 
    {--ownerId= : ID of current user}
    {--inspChklstId= : ID checklist id}
    {--inspTmplShtId= : ID template sheet id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Checklist sheet by cloning a template sheet.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ownerId = $this->input->getOption('ownerId');
        $inspChklstId = $this->input->getOption("inspChklstId");
        $inspTmplShtId = $this->input->getOption("inspTmplShtId");
        $inspChklst = Qaqc_insp_chklst::findOrFail($inspChklstId);
        $inspTmplSht = Qaqc_insp_tmpl_sht::findOrFail($inspTmplShtId);

        $superProps = SuperProps::getFor($this->type);
        $default_assignee_1 = $superProps['props']['_assignee_1']['default-values']['default_value'];
        $default_assignee_2 = $superProps['props']['_assignee_2']['default-values']['default_value'];
        $default_getMonitors1 = $superProps['props']['_getMonitors1']['default-values']['default_value'];

        // $prodOrderId = $this->input->getOption('prodOrderId');
        // $inspTmplId = $this->input->getOption('inspTmplId');
        // $prodOrder = Prod_order::findOrFail($prodOrderId);
        // $qaqcInspTmpl = Qaqc_insp_tmpl::findOrFail($inspTmplId);
        if (!$ownerId) {
            $this->info("Owner ID:{$ownerId} doesn't exist");
            return Command::FAILURE;
        }
        if (!$inspChklst) {
            $this->info("Checklist ID:{$inspChklst} doesn't exist");
            return Command::FAILURE;
        }
        if (!$inspTmplSht) {
            $this->info("Template Sheet ID:{$inspTmplSht} doesn't exist");
            return Command::FAILURE;
        }
        try {
            $newSheet = Qaqc_insp_chklst_sht::create([
                'name' => $inspTmplSht->name,
                'description' => $inspTmplSht->description,
                'slug' => (new All_SlugifyByName())($inspTmplSht->slug, 'qaqc_insp_chklst_sht', ''),
                'qaqc_insp_chklst_id' => $inspChklstId,
                'qaqc_insp_tmpl_sht_id' => $inspTmplSht->id,
                'owner_id' => $ownerId,
                'status' => 'in_progress',
                'progress' => 0,
                'prod_discipline_id' => $inspTmplSht->prod_discipline_id,
                'order_no' => $inspTmplSht->order_no,

                'assignee_1' => $default_assignee_1,
                'assignee_2' => $default_assignee_2,
            ]);

            $defaultMonitors = explode(",", $default_getMonitors1);
            $defaultMonitors = array_map(fn ($i) => $i * 1, $defaultMonitors);
            // Log::info($defaultMonitors);
            $newSheet->syncCheck("getMonitors1", "App\Models\User", $defaultMonitors);

            $thirdPartyList = $inspTmplSht->getDefExtInsp()->pluck('id')->toArray();
            // Log::info($thirdPartyList);
            $newSheet->syncCheck("signature_qaqc_chklst_3rd_party_list", "App\Models\User", $thirdPartyList);

            $lines = $inspTmplSht->getLines;
            foreach ($lines as $qaqcInspTmplLine) {
                Qaqc_insp_chklst_line::create([
                    'name' => $qaqcInspTmplLine->name,
                    'description' => $qaqcInspTmplLine->description,
                    'control_type_id' => $qaqcInspTmplLine->control_type_id,
                    'qaqc_insp_group_id' => $qaqcInspTmplLine->qaqc_insp_group_id,
                    'qaqc_insp_control_group_id' => $qaqcInspTmplLine->qaqc_insp_control_group_id,
                    'qaqc_insp_chklst_sht_id' => $newSheet->id,
                    'order_no' => $qaqcInspTmplLine->order_no,
                    'owner_id' => $ownerId,
                ]);
            }
            //<<This id will be use to redirect, please don't add more text into it.
            $this->info($newSheet->id);
            return Command::SUCCESS;
        } catch (\Throwable $th) {

            $this->error($th->getMessage());
            // $this->error($th->getMessage());
            return Command::FAILURE;
        }
    }
}
