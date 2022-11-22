<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Textarea extends Component
{
    public function __construct(private $colName, private $valColName, private $action, private $control, private $labelName)
    {
    }

    public function render()
    {
        $colName = $this->colName;
        $valColName = is_array($this->valColName) || is_object($this->valColName) ? json_encode(str_replace("&quot;", '\"', $this->valColName)) : $this->valColName;
        $action = $this->action;
        $control = $this->control;
        $labelName = $this->labelName;
        return view('components.controls.textarea')->with(compact('colName', 'valColName', 'action', 'control', 'labelName'));
    }
}
