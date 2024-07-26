<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\RegexReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class IdNameLink extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataLine = [],
        private $routeStr = '',
    ) {
    }


    public function render()
    {
        $routeStr = $this->routeStr;
        $dataLine = $this->dataLine;
        $decodedString = html_entity_decode($routeStr);
        $route = RegexReport::preg_match_all($decodedString, $dataLine);

        $routeName = "";
        try {
            $href = eval('return ' . $route . ';');
            $request = Request::create($href);
            $route = app('router')->getRoutes()->match($request);
            $routeName = $route->getName();
        } catch (Exception $e) {
            $href = "#";
        }

        return function (array $data) use ($href, $routeName) {
            $content = $data["slot"];
            $content->__toString();
            $routeExits =  (Route::has($routeName));
            $color =  $routeExits ? "blue" : "red";
            $a = "<p class='p-2'><a href='$href' class='text-{$color}-500'>{$content}</a></p>";
            $hiddenInput = "<input type='hidden' value='{$content}' name='abc' />";
            return $a . $hiddenInput;
        };
    }
}
