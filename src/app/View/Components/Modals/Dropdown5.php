<?php

namespace App\View\Components\Modals;

use App\Utils\ClassList;
use Illuminate\View\Component;

class Dropdown5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $dataSource,
        private $selected = null,
        private $readOnly = false,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modals.dropdown5',[
            'name' => $this->name,
            'dataSource' => $this->dataSource,
            'selected' => $this->selected,
            'classList' => ClassList::DROPDOWN,
            'readOnly' => $this->readOnly,
        ]);
    }
}
