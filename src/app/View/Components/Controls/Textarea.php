<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class Textarea extends Component
{
    public function __construct(
        private $name,
        private $value,
        private $colType,
    ) {
    }

    public function render()
    {
        $name = $this->name;

        $arrContent = gettype($this->value) === "array" ? $this->value : json_decode($this->value, true);
        $_value = $this->colType === 'json' ? json_encode($arrContent, JSON_PRETTY_PRINT) : $this->value;
        $value = $_value === 'null' ? "" : $_value;

        return view('components.controls.textarea')->with(compact('name', 'value'));
    }
}
