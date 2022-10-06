<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Text extends Component
{

    private $colName;
    private $valColName;
    private $action;
    public function __construct($colName, $valColName, $action)
    {
        $this->colName = $colName;
        $this->valColName = $valColName;
        $this->action = $action;
    }


    public function render()
    {
        $colName = $this->colName;
        $valColName = $this->valColName;
        $action = $this->action;
        return view('components.controls.text')->with(compact('colName', 'valColName', 'action'));
    }
}
