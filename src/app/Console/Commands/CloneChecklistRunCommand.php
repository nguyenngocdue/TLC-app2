<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\CloneRunTrait;
use App\Models\Qaqc_insp_chklst_run;
use Illuminate\Console\Command;

class CloneChecklistRunCommand extends Command
{
    use CloneRunTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:cloneRun 
    {--idRun= : ID Checklist Run}';

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
        if (!$qaqcInspChklstRun = Qaqc_insp_chklst_run::find($idQaqcInspChklstRun)) {
            $this->info("Qaqc_insp_chklst_run ID:{$idQaqcInspChklstRun} doesn't exist");
            return Command::FAILURE;
        }
        try {
            $newQaqcInspChklstRun = $qaqcInspChklstRun->replicate();
            $newQaqcInspChklstRun->save();
            $this->cloneRun($qaqcInspChklstRun, $newQaqcInspChklstRun);
            $this->info("Clone Qaqc_insp_chklst_run ID:{$qaqcInspChklstRun->id} Successfully");
            return $newQaqcInspChklstRun->id;
        } catch (\Throwable $th) {
            $this->error("Error $th");
            return Command::FAILURE;
        }
    }
}
