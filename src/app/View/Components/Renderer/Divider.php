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
    public function __construct()
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
            $slot = $data['slot'];
            return "<div class='bg-body -mx-4 pt-6 pb-2 text-lg font-medium' style='width:105%;'>{$slot}</div>";
        };
        // return "<div class='no-print w-full h-6 bg-gray-100 relative'>
        //     <div class=' w-full h-full top-0 absolute bg-gray-100 dark:bg-gray-700 translate-x-[-16px] '></div>
        //     HELLO
        //     <div class=' w-full h-full top-0 absolute bg-gray-100 dark:bg-gray-700 translate-x-[16px] '></div>
        // </div>";
    }
}
