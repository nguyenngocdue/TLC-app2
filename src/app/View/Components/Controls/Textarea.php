<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Textarea extends Component
{
    public function __construct(private $colName, private $valColName, private $action, private $control)
    {
    }

    public function render()
    {
        $colName = $this->colName;
        $valColName = str_replace("&quot;", '\"', $this->valColName);
        // dd($valColName);
        $action = $this->action;
        $control = $this->control;
        return view('components.controls.textarea')->with(compact('colName', 'valColName', 'action', 'control'));
    }
}
