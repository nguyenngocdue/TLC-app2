<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Toggle extends Component
{
    public function __construct(private $colName, private $valColName)
    {
    }

    public function render()
    {
        $colName = $this->colName;
        $valColName = $this->valColName;
        return view('components.controls.toggle')->with(compact('colName', 'valColName'));
    }
}
