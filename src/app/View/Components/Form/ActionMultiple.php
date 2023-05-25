<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class ActionMultiple extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = null,
        private $restore = false,
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
        if (app()->present()) {
            return view('components.form.action-multiple', [
                'type' => $this->type,
                'restore' => $this->restore,
            ]);
        }
        return '';
    }
}
