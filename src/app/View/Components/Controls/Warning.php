<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class Warning extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.controls.warning');
    }
}
