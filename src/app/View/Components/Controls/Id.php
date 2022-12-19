<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Id extends Component
{
    public function __construct(private $colName, private $value, private $action, private $control, private $label)
    {
    }

    public function render()
    {
        $colName = $this->colName;
        $value = $this->value;
        $action = $this->action;
        $control = $this->control;
        $label = $this->label;
        return view('components.controls.id')->with(compact('colName', 'value', 'action', 'control', 'label'));
    }
}
