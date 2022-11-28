<?php

namespace App\Listeners;

use App\Events\EntityCreatedEvent;
use App\View\Components\Formula\All_ConcatNameWith123;
use App\View\Components\Formula\All_SlugifyByName;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShouldUpdateFieldsListener implements ShouldQueue
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
     * @param  \App\Events\EntityCreatedEvent  $event
     * @return void
     */
    public function handle(EntityCreatedEvent $event)
    {
        // All_ConcatNameWith123::All_ConcatNameWith123($event);
        // All_SlugifyByName::All_SlugifyByName($event);
    }
}
