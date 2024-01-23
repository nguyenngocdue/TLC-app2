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
        $str = "<div class='h-0 page-break-after block '></div>";
        $str .= "<div class='h-10 page-break-after block no-print'></div>";
        return $str;
    }
}
