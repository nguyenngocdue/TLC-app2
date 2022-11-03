<?php

namespace App\View\Components\Render;

use Illuminate\View\Component;

class Error extends Component
{
    public function render()
    {
        return view('components.feedback.alert');
    }
}
