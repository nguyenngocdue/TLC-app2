<?php

namespace App\View\Components\Controls\InspChklst;

use App\Models\Hse_insp_group;
use App\Models\Qaqc_insp_group;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;

class ItemRenderCheckSheet extends Component
{
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
    ) {
        // dump($item);

    }

    private function getGroups($lines)
    {
        $groupColumn = '';
        $groupNames = [];
        switch ($this->type) {
            case 'qaqc_insp_chklst_shts':
                $groupColumn =  'qaqc_insp_group_id';
                $groupIds = $lines->pluck($groupColumn)->unique();
                $groupNames = Qaqc_insp_group::whereIn('id', $groupIds)->get()->pluck('name', 'id');
                break;
            case 'hse_insp_chklst_shts':
                $groupColumn = 'hse_insp_group_id';
                $groupIds = $lines->pluck($groupColumn)->unique();
                $groupNames = Hse_insp_group::whereIn('id', $groupIds)->get()->pluck('name', 'id');
                break;
            default:
                dump("Error: Unknown group column of type $this->type");
                break;
        }
        return [$groupColumn, $groupNames];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $lines = $this->item->getLines;
        $chklst = $this->item->getChklst;

        $prodOrder = is_null($chklst) ? null : $chklst->getProdOrder;
        $subProject = is_null($prodOrder) ? null : $prodOrder->getSubProject;
        $project = is_null($subProject) ? null : $subProject->getProject;
        $status = $this->item->status ? $this->item->status : 'new';
        $props = SuperProps::getFor($this->type)['props'] ?? [];
        [$groupColumn, $groupNames] = $this->getGroups($lines);
        // dump($groupNames);
        return view(
            'components.controls.insp-chklst.item-render-check-sheet',
            [
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
            ]
        );
    }
}
