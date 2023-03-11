<?php

namespace App\View\Components\AdvancedFilterer;

use Illuminate\View\Component;

class DatePicker2 extends Component
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
        return view('components.advanced-filter.date-picker2', [
            'name' => $this->name,
            'value' => $this->value
        ]);
    }
}
