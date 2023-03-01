<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperWorkflows;
use Illuminate\View\Component;

class Workflow403Checker extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = null,
        private $status = null,
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
        $currentRoleSet = CurrentUser::getRoleSet();
        $sw = SuperWorkflows::getFor($this->type, $currentRoleSet);
        // dump($sw);
        $allowed = $sw['workflows'][$this->status]['capabilities'];
        return view(
            'components.controls.workflow403-checker',
            [
                'allowed' => $allowed,
            ]
        );
    }
}
