<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class Breadcrumb extends Component
{
    public function __construct(private $type)
    {
    }

    public function render()
    {
        $type = Str::plural($this->type);
        return view('components.navigation.breadcrumb')->with(compact('type'));
    }
}
