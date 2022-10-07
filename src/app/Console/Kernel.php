<?php

namespace App\Console;

use App\Console\Commands\CreateControllerEntityCommand;
use App\Console\Commands\CreateTableRelationshipCommand;
use App\Console\Commands\UpdateSideBarConfigCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . "/Commands");

        require base_path("routes/console.php");
    }
    protected $commands = [
        CreateTableRelationshipCommand::class,
        UpdateSideBarConfigCommand::class,
        CreateControllerEntityCommand::class
    ];
}
