<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Breadcrumb extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        $type = CurrentRoute::getTypePlural(); // Str::plural($this->type);
        if (in_array($type, ['dashboards'])) return "";
        $singular = Str::singular($type);
        return view('components.navigation.breadcrumb')->with(compact('type', 'singular'));
    }
}
