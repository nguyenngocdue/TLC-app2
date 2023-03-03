<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class QuarterPicker extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = '',
        private $value = '',
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
        return view('components.controls.quarter-picker', [
            'name' => $this->name,
            'value' => $this->value
        ]);
    }
}
