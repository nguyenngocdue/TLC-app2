<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
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
            $type = $data["attributes"]["type"];
            // dd($data["attributes"]);
            $numberRender = str_pad($id, 6, '0', STR_PAD_LEFT);
            $result = '#' . substr($numberRender, 0, 3) . '.' . substr($numberRender, 3, 6);
            $route_name = "{$type}_edit.edit";
            $route_exits =  (Route::has($route_name));

            $href =  $route_exits ? route($route_name, $id) : "#";
            $color =  $route_exits ? "blue" : "red";

            return "<a href='$href' class='text-{$color}-500'>$result</a>";
        };
    }
}
