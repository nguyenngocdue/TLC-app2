<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Textarea extends Component
{
    public function __construct(private $colName, private $valColName, private $action, private $control, private $labelName, private $colType)
    {
    }

    public function render()
    {
        $colName = $this->colName;
        $colType = $this->colType;

        $arrContent = gettype($this->valColName) === "array" ? $this->valColName : json_decode($this->valColName, true);
        $_valColName = $colType === 'json' ? json_encode($arrContent, JSON_PRETTY_PRINT) : $this->valColName;

        $valColName = $_valColName === 'null' ? "" : $_valColName;
        $action = $this->action;
        $control = $this->control;
        $labelName = $this->labelName;
        return view('components.controls.textarea')->with(compact('colName', 'valColName', 'action', 'control', 'labelName'));
    }
}
