<?php

namespace App\Listeners;

use App\Events\LoggedUserSignInHistoriesEvent;
use App\Models\User_sign_in_history;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoggedUserSignInHistoriesListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\LoggedUserSignInHistoriesEvent  $event
     * @return void
     */
    public function handle(LoggedUserSignInHistoriesEvent $event)
    {
        $uid = $event->{'uid'};
        $ip = $event->{'ip'};
        $time = $event->{'time'};
        User_sign_in_history::create([
            'ip_address' => $ip,
            'owner_id' => $uid,
            'time' => Carbon::parse($time),
        ]);
    }
}
