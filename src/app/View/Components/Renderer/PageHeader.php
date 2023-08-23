<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\Json\SuperWorkflows;
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
        return view('components.renderer.page-header', [
            'action' => $action,
            'superProps' => SuperProps::getFor($type),
            'superWorkflows' => SuperWorkflows::getFor($type),
        ]);
    }
}
