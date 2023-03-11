<?php

namespace App\View\Components\AdvancedFilter;

use Illuminate\View\Component;

class WeekPicker extends Component
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
        return view('components.advanced-filter.week-picker', [
            'name' => $this->name,
            'value' => $this->value
        ]);
    }
}
