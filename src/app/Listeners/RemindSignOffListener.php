<?php

namespace App\Listeners;

use App\Events\BroadcastEvents\BroadcastRemindSignOffEvent;
use App\Models\User;
use App\Notifications\RemindSignOffNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class RemindSignOffListener implements ShouldQueue
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
     * @param  \App\Events\BroadcastEvents\BroadcastRemindSignOffEvent  $event
     * @return void
     */
    public function handle(BroadcastRemindSignOffEvent $event)
    {
        //
        $ids = $event->{'ids'};
        $doc = $event->{'doc'};
        // dd($data);
        // dd($ids);
        $users = User::whereIn('id', $ids)->get();
        Notification::send($users, new RemindSignOffNotification($doc));
    }
}
