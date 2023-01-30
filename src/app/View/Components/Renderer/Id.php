<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Id extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = '',
    ) {
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
            $name = $this->name;
            // dd($data["attributes"]);
            $idStr = Str::makeId($id);

            $route_name = "{$type}.edit";
            $route_exits =  (Route::has($route_name));

            $href =  $route_exits ? route($route_name, $id) : "#";
            $color =  $route_exits ? "blue" : "red";

            $a = "<a href='$href' class='text-{$color}-500'>$idStr</a>";
            $hiddenInput = "<input type='hidden' value='$id' name='$name' />";
            return $a . $hiddenInput;
        };
    }
}
