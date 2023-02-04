<?php

namespace App\View\Components\Navigation;

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

        $modelPath = Str::modelPathFrom($singular);
        $first = $modelPath::first();
        $first_id = $first ? $first->id : null;

        $latest = $modelPath::latest()->first();
        $latest_id = $latest ? $latest->id : null;

        $links = [];
        if ($first_id) {
            $links[] = ['href' => route($type . '.edit', $first_id), 'title' => 'View First',];
            $links[] = ['href' => route($type . '.edit', $latest_id), 'title' => 'View Latest',];
        }
        $links[] = ['href' => route($type . '.index'), 'title' => 'View All',];
        $links[] = ['href' => route($type . '.create'), 'title' => 'Add New',];
        $links[] = ['href' => route($singular . '_prp.index'), 'title' => 'Manage Workflows',];
        return view('components.navigation.breadcrumb')->with(compact('links'));
    }
}
