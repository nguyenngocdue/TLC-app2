<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class DateTime extends Component
{
    public function __construct(
        private $name,
        private $value,
        private $control,
    ) {
    }

    public function render()
    {
        $name = $this->name;
        $value = $this->value;
        $control = $this->control;
        $placeholder = Str::getPickerPlaceholder($control);
        return view('components.controls.date-time')->with(compact('name', 'value', 'placeholder'));
    }
}
