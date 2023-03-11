<?php

namespace App\View\Components\AdvancedFilter;

use Illuminate\View\Component;

class PickerTime3 extends Component
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
        return view('components.advanced-filter.picker-time3', [
            'name' => $this->name,
            'value' => $this->value
        ]);
    }
}
