<?php

namespace App\View\Components\Renderer;

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
        return view(
            'components.renderer.item-render-check-sheet',
            [
                'chklst' => $chklst,
                'item' => $this->item,
                'lines' => $lines,
                'subProject' => $sub_project,
                'project' => $project,
            ]
        );
    }
}
