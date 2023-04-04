<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class CheckboxColumn extends Component
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
            $type = $data['attributes']['type'];
            $id = $id = $data["slot"];
            $name = $type . '[]';
            return "
            <input type='checkbox' value='$id' name='$name'>
            ";
        };
    }
}
