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
        $str = "<div style='page-break-after:always!important' class=' flex justify-center align-middle w-full h-6 relative'>";
        $str .= "<div class=' w-full h-full top-0 z-0 absolute bg-gray-100 translate-x-[-80px] no-print'></div>";
        $str .= "<div class=' w-full h-full top-0 z-0 absolute bg-gray-100 translate-x-[80px] no-print'></div>";
        $str .= "<span class='no-print absolute text-black-300 font-thin text-lg '>— ✄ — ✄ — ✄ —</span>";
        $str .= "</div>";
        return $str;
    }
}
