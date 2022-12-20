<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class PageBreak extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return "<div style='page-break-after:always '></div>
        ";
    }
}
