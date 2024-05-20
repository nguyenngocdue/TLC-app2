<?php

namespace App\Console;

// use App\Console\Commands\CreateControllerEntityCommand;
// use App\Console\Commands\CreateTableRelationshipCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

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
        $schedule
            ->call(function () {
                Log::info("Schedule is running.");
            })
            ->everyMinute()
            ->appendOutputTo("storage/logs/schedule_test_minute.log")
            ->description("Every minute: Schedule is running.");

        $schedule
            ->call(fn () => event(new \App\Events\SignOffRemindEvent()))
            // ->weekly()
            // ->mondays()
            // ->wednesdays()
            // ->fridays()
            // ->at('10:00')
            ->cron('0 10 * * 1,3,5') // 0 minute, 10 hour, any day of the month, any month, Monday/Wednesday/Friday
            ->timezone('America/New_York')
            ->appendOutputTo("storage/logs/schedule_remind_signoff.log")
            ->description("EVERY MON/WED/FRI at 10AM US Time: SignOffRemindEvent emitted from Schedule.");


        // transfer database from Diginet Data 
        $schedule
            ->call(function () {
                event(new \App\Events\TransferDiginetDataEvent());
            })
            ->cron('0 22 * * *') // 0 minute, 22:00, every day of month, every month, day of week
            ->timezone("Asia/Bangkok")
            ->appendOutputTo("storage/logs/schedule_transfer_diginet_data.log")
            ->description("Daily at 10PM Bangkok Time: TransferDiginetEvent emitted from schedule.");
    }

    /**`
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
        // CreateTableRelationshipCommand::class,
        // CreateControllerEntityCommand::class
    ];
}
