<?php

namespace App\View\Components\AdvancedFilter;

use App\Utils\ClassList;
use Illuminate\View\Component;

class PickerDate3 extends Component
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
        return view('components.advanced-filter.picker-date3', [
            'name' => $this->name,
            'value' => $this->value,
            'classListInputGroupText' => ClassList::ADV_FLT_INPUT_GROUP_TEXT3,
            'classListFormInput' => ClassList::ADV_FLT_FORM_INPUT3,
        ]);
    }
}
