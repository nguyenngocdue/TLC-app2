<?php

namespace App\View\Components\Renderer\Editable;

use Illuminate\View\Component;

class ModalBroadcastNotification extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.renderer.editable.modal-broadcast-notification');
    }
}
