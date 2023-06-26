<?php

namespace App\View\Components\Controls\InspChklst;

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
            ]
        );
    }
}
