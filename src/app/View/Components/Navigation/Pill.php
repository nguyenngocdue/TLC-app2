<?php

namespace App\View\Components\Navigation;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Pill extends Component
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

        $as = CurrentRoute::getControllerAs();

        $links = [];
        $links[] = ['href' => route($singular . '_prp.index'), 'disabled' => strpos($as, '_prp.') !== false, 'title' => 'Props',];
        $links[] = ['href' => route($singular . '_rls.index'), 'disabled' => strpos($as, '_rls.') !== false, 'title' => 'Relationships',];
        $links[] = ['href' => route($singular . '_ltn.index'), 'disabled' => strpos($as, '_ltn.') !== false, 'title' => 'Listeners',];
        $links[] = ['href' => route($singular . '_stt.index'), 'disabled' => strpos($as, '_stt.') !== false, 'title' => 'Statuses',];

        return view('components.navigation.pill')->with(compact('links'));
    }
}
