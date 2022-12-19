<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Text extends Component
{

    public function __construct(private $colName, private $value, private $action, private $strTimeControl, private $control, private $label)
    {
    }

    public function render()
    {
        $colName = $this->colName;
        $value = $this->value;
        $action = $this->action;
        $control = $this->control;
        $label = $this->label;
        $timeControls = $this->strTimeControl;
        return view('components.controls.text')->with(compact('colName', 'value', 'action', 'timeControls', 'control', 'label'));
    }
}
