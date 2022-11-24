<?php

namespace App\Listeners;

use App\Events\CreateEventEntity;
use App\View\Components\Formular\Allconcat_name_with_123;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ShouldUpdateFieldsListener
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
     * @param  \App\Events\CreateEventEntity  $event
     * @return void
     */
    public function handle(CreateEventEntity $event)
    {
        Allconcat_name_with_123::Allconcat_name_with_123($event);
    }
}
