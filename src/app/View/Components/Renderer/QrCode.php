<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class QrCode extends Component
{
    static $count = 0;
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
            $dataLine = $data["attributes"]["dataLine"];
            $qrCodeApps = LibApps::getByShowRenderer('qr-app-renderer');
            // dump($qrCodeApps);
            // $qrCodeApps = JsonControls::getQrCodeApps();
            // if (in_array($type, $qrCodeApps)) {
            //     $slug = $dataLine['slug'] ?? '';
            //     [$routeExits, $href] = $this->checkRouteShowUsingIdOrSlug($type, $slug, true);
            // } else {
            //     [$routeExits, $href] =  $this->checkRouteShowUsingIdOrSlug($type, $id);
            // }
            $needToShowSlug = in_array(Str::singular($type), $qrCodeApps);
            $id_or_slug = $needToShowSlug ?  $dataLine['slug'] : $id;
            [$routeExits, $href] = $this->checkRouteShowUsingIdOrSlug($type, $id_or_slug);
            // dump($id);
            $color =  $routeExits ? "blue" : "red";
            $icon = "<i class='fa-duotone fa-qrcode'></i>";
            $content = $this->contentPopover($id . '-canvas', $href, static::$count);
            $popover = "<x-renderer.popover id='$id' content='$content'/>";
            $hyperlink_qr = "<a href='$href' data-popover-target='$id' class='inline-block text-{$color}-500'>$icon</a>" . "$popover";

            return $hyperlink_qr;
        };
    }
    private function checkRouteShowUsingIdOrSlug($type, $params)
    {
        // $routeName = $isSlug ? "{$type}.showQRApp" : "{$type}.show";
        $routeName = CurrentRoute::getCurrentIsTrashed() ? "{$type}.showTrashed" : "{$type}.show";
        $routeExits =  (Route::has($routeName));
        $href =  $routeExits ? route($routeName, $params) : "#";
        return [$routeExits, $href];
    }
    private function contentPopover($id, $href, $count)
    {
        static::$count++;
        return '
                <div id="' . $count . '-' . $id . '"class="items-center flex justify-center"></div>
                <div class="items-center">
                    <span class font-medium>Scan this code by your phone</span>
                    <p class="text-xs font-medium">' . $href . '</p>
                </div>
                <script>
                    new QRCode(document.getElementById("' . $count . '-' . $id . '"),"' . $href . '",)
                </script>
                ';
    }
}
