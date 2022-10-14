<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Textarea extends Component
{
    private $colName;
    private $valColName;
    private $action;
    private $control;
    public function __construct($colName, $valColName, $action, $control)
    {
        // dd(str_replace("&quot;", '\"', $valColName));
        $this->colName = $colName;
        $this->valColName = $valColName;
        $this->action = $action;
        $this->control = $control;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
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
