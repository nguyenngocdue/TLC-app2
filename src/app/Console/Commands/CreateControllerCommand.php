<?php

namespace App\Console\Commands;

use App\Utils\Support\Entities;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CreateControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entities:controller';

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
        foreach (Entities::getAllSingularNames() as $entityName) {
            Artisan::call('ndc:controller', ['name' => $entityName,]);
        }
        return Command::SUCCESS;
    }
}
