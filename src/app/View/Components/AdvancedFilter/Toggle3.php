<?php

namespace App\View\Components\AdvancedFilter;

use App\Utils\ClassList;
use Illuminate\View\Component;

class Toggle3 extends Component
{
    public function __construct(
        private $name,
        private $value,
        private $readOnly = false,
    ) {
    }

    public function render()
    {
        $name = $this->name;
        $value = $this->value;
        $readOnly = $this->readOnly;
        return view('components.controls.toggle2', [
            'name' => $name,
            'value' => $value,
            'readOnly' => $readOnly,
            'classList' => ClassList::TOGGLE,
        ]);
    }
}
