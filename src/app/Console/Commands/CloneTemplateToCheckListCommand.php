<?php

namespace App\Console\Commands;

use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_tmpl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloneTemplateToCheckListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:clone 
    {--idTmpl= : ID template Qaqc_insp_tmpls }
    {--idChklst= : ID Qaqc_insp_chklst}';

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
        $idQaqcInspTmpl = $this->input->getOption('idTmpl');
        $idQaqcInspChklst = $this->input->getOption('idChklst');
        $qaqcInspTmpl = Qaqc_insp_tmpl::find($idQaqcInspTmpl);
        if (!Qaqc_insp_chklst::find($idQaqcInspChklst)) {
            $this->info("Qaqc_insp_chklst ID:{$idQaqcInspChklst} doesn't exist");
            return Command::FAILURE;
        }
        $qaqcInspTmplLines = $qaqcInspTmpl->getLines;
        try {
            foreach ($qaqcInspTmplLines as $qaqcInspTmplLine) {
                Qaqc_insp_chklst_line::create([
                    'name' => $qaqcInspTmplLine->name,
                    'description' => $qaqcInspTmplLine->description,
                    'control_type' => $qaqcInspTmplLine->control_type,
                    'qaqc_insp_chklst_id' => $idQaqcInspChklst,
                    'qaqc_insp_sheet_id' => $qaqcInspTmplLine->qaqc_insp_sheet_id,
                    'qaqc_insp_group_id' => $qaqcInspTmplLine->qaqc_insp_group_id,
                    'qaqc_insp_control_value_id' => null,
                    'qaqc_insp_control_group_id' => $qaqcInspTmplLine->qaqc_insp_control_group_id,
                ]);
            }
            $this->info("Clone Qaqc_insp_tmpl ID:{$idQaqcInspTmpl} to Qaqc_insp_chklst ID : {$idQaqcInspChklst} Successfully");
            return Command::SUCCESS;
        } catch (\Throwable $th) {
            $this->error("Error $th");
            return Command::FAILURE;
        }
    }
}
