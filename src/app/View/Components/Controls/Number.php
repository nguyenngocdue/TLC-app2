<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class Number extends Component
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
        return view('components.controls.number')->with(compact('name', 'value', 'readOnly'));
    }
}
