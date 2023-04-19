<?php

namespace App\View\Components\Controls\InspChklst;

use Illuminate\View\Component;

class ItemRenderCheckSheet extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id,
        private $item,
        private $type,
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
        // getRun->getSheet->getChklst->prodOrder->subProject
        $lines = $this->item->getLines;
        // $lines = $this->item->getRuns[0]->getLines;
        $chklst = $this->item->getChklst;
        $prodOrder = $chklst->prodOrder;
        $sub_project = $prodOrder->subProject;
        $project = $sub_project->getProject;
        // dump($chklst);
        $signatures = $this->item->getShtSigs;
        $status = $this->item->status ? $this->item->status : 'in_progress';
        return view(
            'components.controls.insp-chklst.item-render-check-sheet',
            [
                'chklst' => $chklst,
                'item' => $this->item,
                'lines' => $lines,
                'subProject' => $sub_project,
                'project' => $project,
                'status' => $status,
                'signatures' => $signatures,
                'type' => $this->type,
            ]
        );
    }
}
