<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Toggle extends Component
{
    public function __construct(private $colName, private $value, private $label)
    {
    }

    public function render()
    {
        $colName = $this->colName;
        $value = $this->value;
        $label = $this->label;
        return view('components.controls.toggle')->with(compact('colName', 'value', 'label'));
    }
}
