<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class Breadcrumb extends Component
{

    private $type;
    public function __construct($type)
    {
        $this->type = $type;
    }

    public function render()
    {
        $type = Str::plural($this->type);
        return view('components.controls.breadcrumb')->with(compact('type'));
    }
}
