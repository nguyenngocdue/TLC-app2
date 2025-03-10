<?php

namespace App\View\Components\Controls;

use App\Utils\ClassList;
use Illuminate\View\Component;

class Number2 extends Component
{

    public function __construct(
        private $name,
        private $value,
        private $numericScale = 0,
        private $readOnly = false,
    ) {
    }

    public function render()
    {
        return view('components.controls.number2', [
            'name' => $this->name,
            'value' => $this->value,
            'readOnly' => $this->readOnly,
            'numericScale'=> $this->numericScale,
            'classList' => ClassList::TEXT,
        ]);
    }
}
