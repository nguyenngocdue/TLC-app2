<?php

namespace App\View\Components\Modals;

use Illuminate\View\Component;

class ModalOverDueDocuments extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $modalId,
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
        return view(
            'components.modals.modal-over-due-documents',
            [
                'modalId' => $this->modalId,
            ]
        );
    }
}
