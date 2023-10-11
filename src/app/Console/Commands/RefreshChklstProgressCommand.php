<?php

namespace App\Console\Commands;

use App\Events\UpdateChklstProgressEvent;
use Illuminate\Console\Command;

class RefreshChklstProgressCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nnd:refreshChklstProgress
    {--subProjectId= : Sub Project ID}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh checklist progresses base on all subsequence sheets progresses';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("This command is OBSOLETE.");
        // try {
        //     $subProjectId = $this->input->getOption('subProjectId');
        //     if (!$subProjectId) {
        //         $this->error('subProjectId is missing.');
        //         return Command::FAILURE;
        //     }
        //     event(new UpdateChklstProgressEvent($subProjectId));
        //     $this->info("Program event fired, and listeners are executed.");
        //     return Command::SUCCESS;
        // } catch (\Exception $e) {
        //     $this->error($e->getMessage());
        // }
    }
}
