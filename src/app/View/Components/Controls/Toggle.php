<?php

namespace App\View\Components\Controls;

use App\Utils\ClassList;
use Illuminate\View\Component;

class Toggle extends Component
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
        return view('components.controls.toggle', [
            'name' => $name,
            'value' => $value,
            'readOnly' => $readOnly,
            'classList' => ClassList::TOGGLE,
        ]);
    }
}
