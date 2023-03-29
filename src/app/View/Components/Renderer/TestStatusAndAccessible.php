<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\Json\SuperWorkflows;
use Illuminate\View\Component;

class TestStatusAndAccessible extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $renderId,
        private $status,
        private $action,
        private $statuses,
        private $dryRunToken,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $isAdmin = CurrentUser::isAdmin();
        if (!$isAdmin) return "";
        $superProps = SuperProps::getFor($this->type);
        $superWorkflows = SuperWorkflows::getFor($this->type);
        return view('components.renderer.test-status-and-accessible', [
            'type' => $this->type,
            'renderId' => $this->renderId,
            'status' => $this->status,
            'action' => $this->action,
            'statuses' => $this->statuses,
            'isAdmin' => $isAdmin,
            'dryRunToken' => $this->dryRunToken,
            'superProps' => $superProps,
            'superWorkflows' => $superWorkflows,
        ]);
    }
}
