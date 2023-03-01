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
        private $action = null,
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
        if ($this->status === '') return;
        $currentRoleSet = CurrentUser::getRoleSet();
        $sw = SuperWorkflows::getFor($this->type, $currentRoleSet);
        // dd($sw);
        switch ($this->action) {
            case 'create':
                $allowed = true;
                break;
            case 'edit':
                if (!isset($sw['workflows'][$this->status])) {
                    dump("Orphan status [{$this->status}] detected, please fix this or you will not be able to edit this document.");
                    $allowed = false;
                } else {
                    $allowed = $sw['workflows'][$this->status]['capabilities'];
                }
                break;
            default:
                $allowed = false;
        }
        return view(
            'components.controls.workflow403-checker',
            [
                'allowed' => $allowed,
            ]
        );
    }
}
