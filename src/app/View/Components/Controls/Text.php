<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Text extends Component
{

    private $columnName;
    private $valColName;
    public function __construct($columnName, $valColName)
    {
        $this->columnName = $columnName;
        $this->valColName = $valColName;
    }


    public function render()
    {
        $columnName = $this->columnName;
        $valColName = $this->valColName;
        return view('components.controls.text')->with(compact('columnName', 'valColName'));
    }
}
