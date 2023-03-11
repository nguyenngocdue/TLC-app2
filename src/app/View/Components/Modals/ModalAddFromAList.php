<?php

namespace App\View\Components\Modals;

use App\Models\User_team_ot;
use Illuminate\View\Component;

class ModalAddFromAList extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $modalId = null,
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
        $list = User_team_ot::get();
        return view('components.modals.modal-add-from-a-list', [
            'modalId' => $this->modalId,
        ]);
    }
}
