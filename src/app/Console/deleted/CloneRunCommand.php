<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\CloneRunTrait;
use App\Models\Qaqc_insp_chklst_run;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloneRunCommand extends Command
{
    use CloneRunTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:cloneRun 
    {--idRun= : ID Checklist Run}
    {--ownerId= : Owner Checklist Run}
    {--arrayCheckBox= : Array value checkbox}
    {--arrayControlValueId= : Array control value id}';

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
        $idQaqcInspChklstRun = $this->input->getOption('idRun');
        $ownerId = $this->input->getOption('ownerId');
        $arrayValueCheckbox = $this->input->getOption('arrayCheckBox');
        $arrayControlValueId = $this->input->getOption('arrayControlValueId');
        if (!$qaqcInspChklstRun = Qaqc_insp_chklst_run::find($idQaqcInspChklstRun)) {
            $this->info("Qaqc_insp_chklst_run ID:{$idQaqcInspChklstRun} doesn't exist");
            return Command::FAILURE;
        }
        try {
            $newQaqcInspChklstRun = $qaqcInspChklstRun->replicate();
            $newQaqcInspChklstRun->save();
            $qaqcInspChklstRun->update(['owner_id' => $ownerId]);
            $this->cloneRunLine($qaqcInspChklstRun, $newQaqcInspChklstRun, null, $arrayValueCheckbox, $arrayControlValueId);
            $this->info("Clone Qaqc_insp_chklst_run ID:{$qaqcInspChklstRun->id} Successfully");
            return Command::SUCCESS;
        } catch (\Throwable $th) {
            $this->error("Error $th");
            return Command::FAILURE;
        }
    }
}
