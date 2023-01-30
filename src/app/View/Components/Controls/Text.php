<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class Text extends Component
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
        $placeholder = "Type here...";
        return view('components.controls.text')->with(compact('name', 'value', 'placeholder'));
    }
}
