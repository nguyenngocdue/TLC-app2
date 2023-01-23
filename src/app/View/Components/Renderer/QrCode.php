<?php

namespace App\View\Components\Renderer;

use App\Utils\ConstantSVG;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class QrCode extends Component
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

            $route_name = "{$type}.show";
            $route_exits =  (Route::has($route_name));

            $href =  $route_exits ? route($route_name, $id) : "#";
            $color =  $route_exits ? "blue" : "red";

            $icon_qr = ConstantSVG::ICON_QR_CODE;
            $hyperlink_qr = "<a href='$href' class='inline-block text-{$color}-500'>$icon_qr</a>";

            return $hyperlink_qr;
        };
    }
}
