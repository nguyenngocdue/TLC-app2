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
        private $action = null,
    ) {
        //
    }

    private function getActionLabel()
    {
        switch ($this->action) {
            case "edit":
                return "Update";
            case "create":
                return "Create";
            default:
                return "??? $this->action ???";
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.controls.action-buttons', [
            'action' => $this->getActionLabel(),
        ]);
    }
}
