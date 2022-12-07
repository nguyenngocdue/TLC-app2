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
        $type = CurrentRoute::getTypePlural();
        $singular = CurrentRoute::getTypeSingular();
        if (in_array($singular, ['dashboard', 'permission', 'workflow'])) return "";

        $model = "App\\Models\\" . Str::ucfirst($singular);
        $first = $model::first();
        $first_id = $first ? $first->id : null;

        $latest = $model::latest()->first();
        $latest_id = $latest ? $latest->id : null;

        return view('components.navigation.breadcrumb')->with(compact('type', 'singular', 'first_id', 'latest_id'));
    }
}
