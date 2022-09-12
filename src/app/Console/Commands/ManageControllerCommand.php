<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ManageControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:manage {dir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create New Manage Directory {dir} Controller';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        mkdir(__DIR__.'/')

        return 0;
    }
}
