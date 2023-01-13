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

        $links = [['href' => route($type . '.index'), 'title' => 'View All',]];
        if ($first_id) {
            $links[] = ['href' => route($type . '.edit', $first_id), 'title' => 'View First',];
            $links[] = ['href' => route($type . '.edit', $latest_id), 'title' => 'View Latest',];
        }
        $links[] = ['href' => route($type . '.create'), 'title' => 'Add New',];
        $links[] = ['href' => route($singular . '_prop.index'), 'title' => 'Props',];
        $links[] = ['href' => route($singular . '_rel.index'), 'title' => 'Relationships',];
        $links[] = ['href' =>  route($singular . '_ltn.index'), 'title' => 'Listeners',];
        $links[] = ['href' =>  route($singular . '_stt.index'), 'title' => 'Statuses',];

        return view('components.navigation.breadcrumb')->with(compact('links'));
    }
}
