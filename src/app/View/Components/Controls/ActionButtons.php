<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class ActionButtons extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $buttonSave,
        private $action,
        private $actionButtons,
        private $propsIntermediate,
        private $type,
        private $isFloatingOnRightSide = false,
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
        if ($this->type == 'qaqc_insp_chklst_shts') {
            if (CurrentUser::get()->isExternalInspector()) return "";
        }
        return view('components.controls.action-buttons', [
            'buttonSave' => $this->buttonSave,
            'action' => $this->action,
            'actionButtons' => $this->actionButtons,
            'propsIntermediate' => $this->propsIntermediate,
            'isFloatingOnRightSide' => $this->isFloatingOnRightSide,
            'showSaveAndReturn' => ($this->type != 'users'),
        ]);
    }
}
