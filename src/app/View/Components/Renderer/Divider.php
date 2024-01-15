<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Divider extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        // private $x = '16px'
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return function (array $data,) {
            return "<div class='no-print w-full h-6 bg-gray-100 relative'>
                        <div class=' w-full h-full top-0 absolute bg-gray-100 dark:bg-gray-700 translate-x-[-16px] '></div>
                        <div class=' w-full h-full top-0 absolute bg-gray-100 dark:bg-gray-700 translate-x-[16px] '></div>
                    </div>";
        };
    }
}
