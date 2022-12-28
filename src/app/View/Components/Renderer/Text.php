<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Text extends Component
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
        return function (array $data) {
            $str = $data['slot'];
            $str = Str::limitWords($str, 10);
            return $str;
        };
    }
}
