<?php

namespace App\Console\Commands;

use App\Events\UpdateChklstProgressEvent;
use Illuminate\Console\Command;

class refreshChklstProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nnd:refreshChklstProgress
    {--subProjectId= : Project ID}
    ';

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
        try {
            $projectId = $this->input->getOption('subProjectId');
            $event = event(new UpdateChklstProgressEvent($projectId));
            if ($event) $this->info('The progress of Inspection Checklists have been successfully updated!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Something went wrong!');
            $this->line("Could you check your command following the instructions: \n php artisan nnd:refreshChklstProgress --subProjectId=[...]");
        }
    }
}
