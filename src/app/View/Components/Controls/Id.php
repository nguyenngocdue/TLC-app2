<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class Id extends Component
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
        return view('components.controls.id')->with(compact('name', 'value'));
    }
}
