<?php

namespace App\Listeners;

use App\Events\RequestSignOffEvent;
use App\Mail\MailRequestSignOff;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RequestSignOffListener
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
     * @param  \App\Events\RequestSignOffEvent  $event
     * @return void
     */
    public function handle(RequestSignOffEvent $event)
    {
        Log::info($event->uids);
        Log::info($event->signableId);
        Log::info($event->signableType);

        $user = User::find($event->uids[0]);

        try {
            Mail::to($user->email)->send(new MailRequestSignOff());
        } catch (\Exception $e) {
            return "Mail Failed to send. Message: " . $e->getMessage();
        }

        // Log::info("Email sent");
    }
}
