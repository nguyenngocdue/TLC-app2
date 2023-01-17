<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class Text extends Component
{

    public function __construct(
        private $colName,
        private $value,
        private $strTimeControl,
        private $control,
        private $label,
    ) {
    }

    public function render()
    {
        $colName = $this->colName;
        $value = $this->value;
        $action = CurrentRoute::getControllerAction();
        $control = $this->control;
        $label = $this->label;
        $timeControls = $this->strTimeControl;
        return view('components.controls.text')->with(compact('colName', 'value', 'action', 'timeControls', 'control', 'label'));
    }
}
