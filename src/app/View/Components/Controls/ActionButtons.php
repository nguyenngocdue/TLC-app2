<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class ActionButtons extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $buttonSave,
        private $action,
        private $actionButtons,
        private $propsIntermediate,
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
        return view('components.controls.action-buttons', [
            'buttonSave' => $this->buttonSave,
            'action' => $this->action,
            'actionButtons' => $this->actionButtons,
            'propsIntermediate' => $this->propsIntermediate,
        ]);
    }
}
