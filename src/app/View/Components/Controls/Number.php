<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class Number extends Component
{

    public function __construct(
        private $colName,
        private $value,
        private $control,
        private $label,
    ) {
    }

    public function render()
    {
        $colName = $this->colName;
        $value = $this->value;
        $action = CurrentRoute::getControllerAction();
        $control = $this->control;
        $label = $this->label;
        return view('components.controls.number')->with(compact('colName', 'value', 'action', 'control', 'label'));
    }
}
