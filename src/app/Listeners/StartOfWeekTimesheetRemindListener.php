<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class StartOfWeekTimesheetRemindListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
        Log::info("StartOfWeekTimesheetRemindListener is triggered.");
        $users = User::query()
            ->where('resigned', false)
            ->where('time_keeping_type', 2)
            ->get();
        $count = 1;
        foreach ($users as $user) {
            // $user->notify(new StartOfWeekTimesheetRemindNotification());
            Log::info(($count++) . " User #" . str_pad($user->id, 4, '0', STR_PAD_LEFT) . " - " . $user->name . " notified.");
        }
    }
}
