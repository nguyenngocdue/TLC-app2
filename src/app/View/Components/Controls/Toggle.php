<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Toggle extends Component
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
        return view('components.controls.toggle')->with(compact('name', 'value'));
    }
}
