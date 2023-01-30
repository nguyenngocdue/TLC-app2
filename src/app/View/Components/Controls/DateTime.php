<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class DateTime extends Component
{

    public function __construct(
        private $name,
        private $value,
    ) {
    }

    public function render()
    {
        $name = $this->name;
        $value = $this->value;
        $placeholder = "YYYY-MM-DD";
        return view('components.controls.text')->with(compact('name', 'value', 'placeholder'));
    }
}
