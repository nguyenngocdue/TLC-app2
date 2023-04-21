<?php

namespace App\View\Components\AdvancedFilter;

use App\Utils\ClassList;
use Illuminate\View\Component;

class PickerTime3 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $control = '',
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
            'control' => $this->control,
            'name' => $this->name,
            'value' => $this->value,
            'classListInputGroupText' => ClassList::ADV_FLT_INPUT_GROUP_TEXT,
            'classListFormInput' => ClassList::ADV_FLT_FORM_INPUT,
        ]);
    }
}
