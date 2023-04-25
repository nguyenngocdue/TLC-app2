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
        $projectId = $this->input->getOption('subProjectId');
        return event(new UpdateChklstProgressEvent($projectId));
    }
}
