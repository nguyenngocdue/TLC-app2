<?php

namespace App\Listeners;

use App\Events\BroadcastEvents\BroadcastRemindSignOffEvent;
use App\Models\User;
use App\Notifications\RemindSignOffNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class RemindSignOffListener implements ShouldQueue
{
    use Queueable;
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
        $ids = $event->{'ids'};
        $doc = $event->{'doc'};
        $users = User::whereIn('id', $ids)->get();
        Notification::send($users, new RemindSignOffNotification($doc));
    }
}
