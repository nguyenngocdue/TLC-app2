<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Session;

class UpdateDiginetDataListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Session::flash('toastr_message', 'Your message here');
        return redirect()->route('handle-click');
    }
}
