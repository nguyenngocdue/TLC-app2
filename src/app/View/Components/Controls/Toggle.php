<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Toggle extends Component
{
    public function __construct(private $colName, private $valColName, private $labelName)
    {
    }

    public function render()
    {
        $colName = $this->colName;
        $valColName = $this->valColName;
        $labelName = $this->labelName;
        return view('components.controls.toggle')->with(compact('colName', 'valColName', 'labelName'));
    }
}
