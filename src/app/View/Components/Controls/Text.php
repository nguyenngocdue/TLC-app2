<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Text extends Component
{

    private $colName;
    private $valColName;
    private $action;
    private $strTimeControl;
    private $control;
    public function __construct($colName, $valColName, $action, $strTimeControl, $control)
    {
        $this->colName = $colName;
        $this->valColName = $valColName;
        $this->action = $action;
        $this->strTimeControl = $strTimeControl;
        $this->control = $control;
    }


    public function render()
    {
        $colName = $this->colName;
        $valColName = $this->valColName;
        $action = $this->action;
        $timeControls = $this->strTimeControl;
        $control = $this->control;
        return view('components.controls.text')->with(compact('colName', 'valColName', 'action', 'timeControls', 'control'));
    }
}
