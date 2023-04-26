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
    protected $description = 'Refresh checklist progresses base on all subsequence sheets progresses.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $subProjectId = $this->input->getOption('subProjectId');
            if (!$subProjectId) {
                $this->error('subProjectId is missing.');
                return Command::FAILURE;
            }
            $event = event(new UpdateChklstProgressEvent($subProjectId));
            // dump($event);
            $result = $event[0][0] . "/" . $event[0][1];
            if ($event) $this->info("The progress of Inspection Checklists have been successfully updated: $result");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
