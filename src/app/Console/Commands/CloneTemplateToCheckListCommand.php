<?php

namespace App\Console\Commands;

use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl;
use App\Console\Commands\Traits\CloneRunTrait;
use App\Models\Qaqc_insp_chklst_run;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloneTemplateToCheckListCommand extends Command
{
    use CloneRunTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:clone 
    {--idT= : ID template Qaqc_insp_tmpls }
    {--idC= : ID Qaqc_insp_chklst}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $idQaqcInspTmpl = $this->input->getOption('idT');
        $idQaqcInspChklst = $this->input->getOption('idC');
        $qaqcInspTmpl = Qaqc_insp_tmpl::find($idQaqcInspTmpl);
        if (!Qaqc_insp_chklst::find($idQaqcInspChklst)) {
            $this->info("Qaqc_insp_chklst ID:{$idQaqcInspChklst} doesn't exist");
            return Command::FAILURE;
        }
        try {
            $qaqcInspTmplSheets = $qaqcInspTmpl->getSheets;
            if (count($qaqcInspTmplSheets) > 0) {
                foreach ($qaqcInspTmplSheets as $qaqcInspTmplSheet) {
                    $qaqcInspChklstSht = Qaqc_insp_chklst_sht::create([
                        'name' => $qaqcInspTmplSheet->name,
                        'description' => $qaqcInspTmplSheet->description,
                        'slug' => $qaqcInspTmplSheet->slug,
                        'qaqc_insp_chklst_id' => $idQaqcInspChklst,
                    ]);
                    $qaqcInspTmplRun = $qaqcInspTmplSheet->getRuns[0];
                    $qaqcInspChklstRun = Qaqc_insp_chklst_run::create([
                        'qaqc_insp_chklst_sht_id' => $qaqcInspTmplRun->id,
                    ]);
                    $this->cloneRunLine($qaqcInspTmplRun, null, $qaqcInspChklstRun->id);
                }
            }
            $this->info("Clone Qaqc_insp_tmpl ID:{$idQaqcInspTmpl} to Qaqc_insp_chklst ID : {$idQaqcInspChklst} Successfully");
            return Command::SUCCESS;
        } catch (\Throwable $th) {
            $this->error("Error $th");
            return Command::FAILURE;
        }
    }
}
