<?php

namespace App\View\Components\Modals;

use Illuminate\View\Component;

class ModalAddInspectorFromList extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $modalId = null,
        private $type = null,
        private $action = null,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modals.modal-add-inspector-from-list', [
            'modalId' => $this->modalId,
            'type' => $this->type,
            'action' => $this->action,
        ]);
    }
}
