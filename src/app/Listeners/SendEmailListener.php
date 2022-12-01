<?php

namespace App\Listeners;

use App\Events\SendEmailItemCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailListener
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
     * @param  \App\Events\SendEmailItemCreated  $event
     * @return void
     */
    public function handle(SendEmailItemCreated $event)
    {
        $item = Post::find($event->id)->toArray();
    }
}
