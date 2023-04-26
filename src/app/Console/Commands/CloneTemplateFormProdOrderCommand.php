<?php

namespace App\Console\Commands;

use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Models\Prod_order;
use App\Models\Qaqc_insp_chklst_line;
use App\View\Components\Formula\All_SlugifyByName;
use Illuminate\Console\Command;

class CloneTemplateFormProdOrderCommand extends Command
{
    // use CloneRunTrait;
    use TraitEntityFormula;

    protected $type;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:createAndClone 
    {--ownerId= : ID of current user}
    {--prodOrderId= : ID production order }
    {--inspTmplId= : ID template Qaqc_insp_tmpls}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Checklist by cloning a template, all subsequence Sheets and Lines';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ownerId = $this->input->getOption('ownerId');
        $prodOrderId = $this->input->getOption('prodOrderId');
        $inspTmplId = $this->input->getOption('inspTmplId');
        $prodOrder = Prod_order::findOrFail($prodOrderId);
        $qaqcInspTmpl = Qaqc_insp_tmpl::findOrFail($inspTmplId);
        if (!$ownerId) {
            $this->info("Owner ID:{$ownerId} doesn't exist");
            return Command::FAILURE;
        }
        if (!$prodOrder) {
            $this->info("Prod Order ID:{$prodOrderId} doesn't exist");
            return Command::FAILURE;
        }
        if (!$qaqcInspTmpl) {
            $this->info("Qaqc_insp_tmpl ID:{$inspTmplId} doesn't exist");
            return Command::FAILURE;
        }
        try {
            $qaqcInspChklst = Qaqc_insp_chklst::create([
                'prod_order_id' => $prodOrderId,
                'name' => $prodOrder->name,
                'slug' => (new All_SlugifyByName())($prodOrder->name, 'qaqc_insp_chklst', ''),
                'owner_id' => $ownerId,
                'qaqc_insp_tmpl_id' => $inspTmplId,
            ]);
            $qaqcInspTmplSheets = $qaqcInspTmpl->getSheets;
            if (count($qaqcInspTmplSheets) > 0) {
                foreach ($qaqcInspTmplSheets as $qaqcInspTmplSheet) {
                    $qaqcInspChklstSht = Qaqc_insp_chklst_sht::create([
                        'name' => $qaqcInspTmplSheet->name,
                        'description' => $qaqcInspTmplSheet->description,
                        'slug' => (new All_SlugifyByName())($qaqcInspTmplSheet->slug, 'qaqc_insp_chklst_sht', ''),
                        'qaqc_insp_chklst_id' => $qaqcInspChklst->id,
                        'qaqc_insp_tmpl_sht_id' => $qaqcInspTmplSheet->id,
                        'owner_id' => $ownerId,
                    ]);
                    foreach ($qaqcInspTmplSheet->getLines as $qaqcInspTmplLine) {
                        Qaqc_insp_chklst_line::create([
                            'name' => $qaqcInspTmplLine->name,
                            'description' => $qaqcInspTmplLine->description,
                            'control_type_id' => $qaqcInspTmplLine->control_type_id,
                            'qaqc_insp_group_id' => $qaqcInspTmplLine->qaqc_insp_group_id,
                            'qaqc_insp_control_group_id' => $qaqcInspTmplLine->qaqc_insp_control_group_id,
                            'qaqc_insp_chklst_sht_id' => $qaqcInspChklstSht->id,
                            'owner_id' => $ownerId,
                        ]);
                    }
                }
            }
            $this->info("Created Qaqc_insp_chklst and cloned qaqc_insp_tmpl_sht successfully");
            return Command::SUCCESS;
        } catch (\Throwable $th) {

            $this->error($th->getPrevious()->getMessage());
            // $this->error($th->getMessage());
            return Command::FAILURE;
        }
    }
}
