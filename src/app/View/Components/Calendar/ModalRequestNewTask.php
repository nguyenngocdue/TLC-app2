<?php

namespace App\View\Components\Calendar;

use Illuminate\View\Component;

class ModalRequestNewTask extends Component
{
    public function __construct(
        private $modalId = '',
    ) {}

    function render()
    {
        // dump("Request to add a new Task");
        return view('components.calendar.modal-request-new-task', [
            'modalId' => $this->modalId,
        ]);
    }
}
