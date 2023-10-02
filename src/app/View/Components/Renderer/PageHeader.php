<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\Json\SuperWorkflows;
use App\Utils\Support\JsonControls;
use Illuminate\View\Component;

class PageHeader extends Component
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
        $type = CurrentRoute::getTypePlural();
        $action = CurrentRoute::getControllerAction();
        // dump($type);
        $hidden_apps = JsonControls::getAppsHaveViewAllKanban();
        $visible = (!in_array($type, $hidden_apps));
        // $visible = CurrentUser::isAdmin();
        return view('components.renderer.page-header', [
            'action' => $action,
            'superProps' => SuperProps::getFor($type),
            'superWorkflows' => SuperWorkflows::getFor($type),
            'visible' => $visible,
        ]);
    }
}
