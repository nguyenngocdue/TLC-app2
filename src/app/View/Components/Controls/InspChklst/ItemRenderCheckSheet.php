<?php

namespace App\View\Components\Controls\InspChklst;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitGetGroupChkSht;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class ItemRenderCheckSheet extends Component
{
    use TraitGetGroupChkSht;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource,
        private $action,
        private $type,
        private $modelPath,
        private $status = null,
        private $id = null,
        private $item = null,
        private $allowed = false,
    ) {
        // dump($item);
    }

    private function hasSignatureMulti()
    {
        $sp = SuperProps::getFor($this->type);
        // dump($sp);
        foreach ($sp['props'] as $prop) {
            if ($prop['control'] == 'signature_multi') return true;
        }
        return false;
    }

    function getShowHeader()
    {
        $result = 0;
        foreach ($this->dataSource as $prop) {
            if ($prop['hiddenRow'] !== 'hidden') {
                $result++;
            }
        }
        return $result;
    }

    public function render()
    {
        $lines = $this->item->getLines()->orderBy('order_no')->get();
        $chklst = $this->item->getChklst;

        $prodOrder = is_null($chklst) ? null : $chklst->getProdOrder;
        $subProject = is_null($prodOrder) ? null : $prodOrder->getSubProject;
        $project = is_null($subProject) ? null : $subProject->getProject;
        $status = $this->item->status ? $this->item->status : 'new';
        $props = SuperProps::getFor($this->type)['props'] ?? [];
        [$groupColumn, $groupNames] = $this->getGroups($lines);
        // dump($groupNames);
        $showHeader = $this->getShowHeader();
        //Remove _getMonitors1(), as it will be double submit to Update2
        unset($this->dataSource["_getMonitors1()"]);

        return view(
            'components.controls.insp-chklst.item-render-check-sheet',
            [
                'showHeader' => $showHeader,
                'dataSource' => $this->dataSource,
                'action' => $this->action,
                'modelPath' => $this->modelPath,
                'id' => $this->id,
                'chklst' => $chklst,
                'item' => $this->item,
                'lines' => $lines,
                'subProject' => $subProject,
                'project' => $project,
                'status' => $status,
                'type' => $this->type,
                'props' => $props,
                'groupColumn' => $groupColumn,
                'groupNames' => $groupNames,
                'hasSignatureMulti' => $this->hasSignatureMulti(),
                'showSignOff' => in_array($status, [/*'passed',*/'pending_approval', 'approved', 'rejected']),
                'isExternalInspector' => CurrentUser::isExternalInspector(),
                "allowed" => !!$this->allowed,
            ]
        );
    }
}
