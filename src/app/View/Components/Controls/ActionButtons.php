<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
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
        private $item = null,
    ) {
        //
    }

    private function isCurrentAppHasSignOff()
    {
        $sp = SuperProps::getFor($this->type)['props'];
        foreach ($sp as $key => $value) {
            if (in_array("signature_multi", $value)) return true;
        }
        return false;
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
            if (CurrentUser::get()->isProjectClient()) return "";
        }
        if (in_array($this->type, ['conqa_archives'])) return;

        $isSignOff = $this->isCurrentAppHasSignOff();
        $monitors1 = method_exists($this->item, "getMonitors1") ? $this->item->getMonitors1() : collect();
        $monitors1 = $monitors1->pluck('id')->toArray() ?? [];
        $isPendingApproval = ($this->item->status ?? null) == 'pending_approval';
        // dump("$isSignOff && $isPendingApproval");
        if ($isSignOff && $isPendingApproval) {
            // dump("The case");
            if (!CurrentUser::isAdmin()) {
                if (!in_array(CurrentUser::get()->id, $monitors1)) {
                    // dump("Is not a signoff admin");
                    return;
                }
            }
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
