<?php

namespace App\Console;

// use App\Console\Commands\CreateControllerEntityCommand;
// use App\Console\Commands\CreateTableRelationshipCommand;

use App\Utils\Constant;
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
            ->call(function () {/* Do nothing */
                Log::channel('schedule_heartbeat_channel')->info("Executed at " . date(Constant::FORMAT_DATETIME_ASIAN));
            })
            ->everySixHours()
            // ->appendOutputTo(storage_path("logs/schedule_test_minute.log"))
            ->description("Every 6 hours: Heartbeat at: " . date(Constant::FORMAT_DATETIME_ASIAN));

        $schedule
            ->call(function () {
                event(new \App\Events\SignOffRemindEvent());
                Log::channel('schedule_remind_signoff_channel')->info("Executed at " . date(Constant::FORMAT_DATETIME_ASIAN));
            })
            ->cron('0 10 * * 1,3,5') // 0 minute, 10 hour, any day of the month, any month, Monday/Wednesday/Friday
            ->timezone('America/New_York')
            // ->appendOutputTo(storage_path("logs/schedule_remind_signoff.log"))
            ->description("EVERY MON/WED/FRI at 10:00AM US Time: SignOffRemindEvent emitted from Schedule.");


        // transfer database from Diginet Data 
        $schedule
            ->call(function () {
                event(new \App\Events\TransferDiginetDataEvent());
                Log::channel('schedule_diginet_sync_channel')->info("Executed at " . date(Constant::FORMAT_DATETIME_ASIAN));
            })
            ->cron('0 22 * * *') // 0 minute, 22:00, every day of month, every month, day of week
            ->timezone("Asia/Ho_Chi_Minh")
            // ->appendOutputTo(storage_path("logs/schedule_transfer_diginet_data.log"))
            ->description("Daily at 22:00 Ho_Chi_Minh Time: TransferDiginetEvent emitted from schedule.");

        $schedule
            ->call(function () {
                event(new \App\Events\CleanUpTrashEvent());
                Log::channel('schedule_cleanup_trash_channel')->info("Executed at " . date(Constant::FORMAT_DATETIME_ASIAN));
            })
            ->dailyAt('21:00')
            ->timezone("Asia/Ho_Chi_Minh")
            // ->appendOutputTo(storage_path("logs/schedule_clean_up_trash.log"))
            ->description("Daily at 21:00 Ho_Chi_Minh Time: Clean Up Trash.");;
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
