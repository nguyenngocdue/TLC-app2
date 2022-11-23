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
        $array = json_decode($this->valColName, true);
        $_valColName = $colType === 'json' ? json_encode($array, JSON_PRETTY_PRINT) : $this->valColName;

        $valColName = $_valColName === "\"\"" ? "" : $_valColName;
        // dd($_valColName, $colType, $this->valColName);
        $action = $this->action;
        $control = $this->control;
        return view('components.controls.textarea')->with(compact('colName', 'valColName', 'action', 'control'));
    }
}
