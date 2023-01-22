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
        $links[] = ['href' => route($singular . '_dfv.index'), 'disabled' => strpos($as, '_dfv.') !== false, 'title' => 'Default Values',];
        $links[] = ['href' => route($singular . '_rls.index'), 'disabled' => strpos($as, '_rls.') !== false, 'title' => 'Relationships',];
        $links[] = ['href' => route($singular . '_ltn.index'), 'disabled' => strpos($as, '_ltn.') !== false, 'title' => 'Listeners',];
        $links[] = ['href' => route($singular . '_stt.index'), 'disabled' => strpos($as, '_stt.') !== false, 'title' => 'Statuses',];

        $links[] = ['href' => route($singular . '_tst.index'), 'disabled' => strpos($as, '_tst.') !== false, 'title' => 'Transitions',];
        $links[] = ['href' => route($singular . '_bic.index'), 'disabled' => strpos($as, '_bic.') !== false, 'title' => 'Ball In Court',];
        $links[] = ['href' => route($singular . '_atb.index'), 'disabled' => strpos($as, '_atb.') !== false, 'title' => 'Action Buttons',];
        $links[] = ['href' => route($singular . '_stn.index'), 'disabled' => strpos($as, '_stn.') !== false, 'title' => 'Settings',];

        $links[] = ['href' => route($singular . '_prp.index'), 'disabled' => strpos($as, '_stt6.') !== false, 'title' => 'In Between',];

        $links1 = [];
        $links1[] = ['href' => route($singular . '_vsb.index'), 'disabled' => strpos($as, '_vsb.') !== false, 'title' => 'Visible',];
        $links1[] = ['href' => route($singular . '_rol.index'), 'disabled' => strpos($as, '_rol.') !== false, 'title' => 'Read-Only',];
        $links1[] = ['href' => route($singular . '_rqr.index'), 'disabled' => strpos($as, '_rqr.') !== false, 'title' => 'Required',];
        $links1[] = ['href' => route($singular . '_hdn.index'), 'disabled' => strpos($as, '_hdn.') !== false, 'title' => 'Hidden',];
        $links1[] = ['href' => route($singular . '_rol-wl.index'), 'disabled' => strpos($as, '_rol-wl.') !== false, 'title' => 'Read-Only WhiteList',];
        $links1[] = ['href' => route($singular . '_hdn-wl.index'), 'disabled' => strpos($as, '_hdn-wl.') !== false, 'title' => 'Hidden WhiteList',];
        $links1[] = ['href' => route($singular . '_cpb.index'), 'disabled' => strpos($as, '_cpb.') !== false, 'title' => 'Capabilities',];
        $links1[] = ['href' => route($singular . '_unt.index'), 'disabled' => strpos($as, '_unt.') !== false, 'title' => 'Unit Tests',];


        return view('components.navigation.pill')->with(compact('links', 'links1'));
    }
}
