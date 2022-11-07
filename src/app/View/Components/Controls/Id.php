<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Id extends Component
{
    public function __construct(private $colName, private $valColName, private $action, private $strTimeControl, private $control, private $labelName)
    {
    }

    public function render()
    {
        $colName = $this->colName;
        $valColName = $this->valColName;
        $action = $this->action;
        $timeControls = $this->strTimeControl;
        $control = $this->control;
        $labelName = $this->labelName;
        return view('components.controls.id')->with(compact('colName', 'valColName', 'action', 'timeControls', 'control', 'labelName'));
    }
}
