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

        $linkCrud = [];
        if ($first_id) {
            $linkCrud[] = ['href' => route($type . '.edit', $first_id), 'title' => 'View First',];
            $linkCrud[] = ['href' => route($type . '.edit', $latest_id), 'title' => 'View Latest',];
        }
        $linkCrud[] = ['href' => route($type . '.index'), 'title' => 'View All',];
        $linkCrud[] = ['href' => route($type . '.create'), 'title' => 'Add New',];

        $linkManageJson = [];
        $linkManageJson[] = ['href' => route($singular . '_prp.index'), 'title' => 'Props',];
        $linkManageJson[] = ['href' => route($singular . '_rls.index'), 'title' => 'Relationships',];
        $linkManageJson[] = ['href' =>  route($singular . '_ltn.index'), 'title' => 'Listeners',];
        $linkManageJson[] = ['href' =>  route($singular . '_stt.index'), 'title' => 'Statuses',];

        return view('components.navigation.breadcrumb')->with(compact('linkCrud', 'linkManageJson'));
    }
}
