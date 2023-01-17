<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class Textarea extends Component
{
    public function __construct(
        private $colName,
        private $value,
        private $control,
        private $label,
        private $colType,
    ) {
    }

    public function render()
    {
        $colName = $this->colName;
        $colType = $this->colType;

        $arrContent = gettype($this->value) === "array" ? $this->value : json_decode($this->value, true);
        $_value = $colType === 'json' ? json_encode($arrContent, JSON_PRETTY_PRINT) : $this->value;

        $value = $_value === 'null' ? "" : $_value;
        $action = CurrentRoute::getControllerAction();
        $control = $this->control;
        $label = $this->label;
        return view('components.controls.textarea')->with(compact('colName', 'value', 'action', 'control', 'label'));
    }
}
