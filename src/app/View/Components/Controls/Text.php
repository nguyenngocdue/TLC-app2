<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Text extends Component
{

    private $colName;
    private $valColName;
    public function __construct($colName, $valColName)
    {
        $this->colName = $colName;
        $this->valColName = $valColName;
    }


    public function render()
    {
        $colName = $this->colName;
        $valColName = $this->valColName;
        return view('components.controls.text')->with(compact('colName', 'valColName'));
    }
}
