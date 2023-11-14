<?php

namespace App\Listeners;

use App\Events\RequestSignOffEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

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


        // Log::info("Email sent");
    }
}
