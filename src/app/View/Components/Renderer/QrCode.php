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
            $route_name = "{$type}.show";
            $route_exits =  (Route::has($route_name));
            $href =  $route_exits ? route($route_name, $id) : "#";
            $color =  $route_exits ? "blue" : "red";
            $icon = "<i class='fa-duotone fa-qrcode'></i>";
            $content = $this->contentPopover($id . '-canvas', $href);
            $popover = "<x-renderer.popover id='$id' content='$content'/>";
            $hyperlink_qr = "<a href='$href' data-popover-target='$id' class='inline-block text-{$color}-500'>$icon</a>" . "$popover";

            return $hyperlink_qr;
        };
    }
    private function contentPopover($id, $href)
    {
        return '
                <div id="' . $id . '"class="items-center flex justify-center"></div>
                <div class="items-center">
                    <span class font-medium>Scan this code by your phone</span>
                    <p class="text-xs font-medium">' . $href . '</p>
                </div>
                <script>
                    new QRCode(document.getElementById("' . $id . '"),"' . $href . '",)
                </script>
                ';
    }
}
