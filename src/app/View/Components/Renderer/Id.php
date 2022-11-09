<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Id extends Component
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
            $id = $data["slot"];
            $numberRender = str_pad($id, 6, '0', STR_PAD_LEFT);
            $result = '#' . substr($numberRender, 0, 3) . '.' . substr($numberRender, 3, 6);

            return "<a href='#' class='text-blue-500'>$result</a>";
        };
    }
}
