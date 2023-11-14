<?php

namespace App\Listeners;

use App\Events\UserSignedInEvent;
use App\Models\User_sign_in_history;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserSignedInListener //implements ShouldQueue
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

    public function handle(UserSignedInEvent $event)
    {
        $uid = $event->{'uid'};
        $ip = $event->{'ip'};
        $time = $event->{'time'};
        $info = $event->{'info'};
        User_sign_in_history::create([
            'ip_address' => $ip,
            'owner_id' => $uid,
            'time' => Carbon::parse($time),
            'city_name' => $info['city_name'],
            'country_name' => $info['country_name'],
            'browser' => $info['browser'],
            'browser_version' => $info['browser_version'],
            'platform' => $info['platform'],
            'device' => $info['device'],
        ]);
    }
}
