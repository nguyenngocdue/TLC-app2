<?php

namespace App\View\Components\Reports;

use App\Utils\ClassList;
use Illuminate\View\Component;

class PickerDate1 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = '',
        private $value = '',
        private $title = '',
        private $allowClear = false,

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
        return view('components.reports.picker-date1', [
            'title' => $this->title,
            'name' => $this->name,
            'value' => $this->value,
            'allowClear' => $this->allowClear,
        ]);
    }
}
