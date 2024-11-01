<?php

namespace App\View\Components\Controls\InspChklst;

use Illuminate\View\Component;

class HeaderCheckSheet extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $chklst,
        private $item,
        private $subProject,
        private $project,
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
        return view('components.controls.insp-chklst.header-check-sheet', [
            'chklst' => $this->chklst,
            'item' => $this->item,
            'subProject' => $this->subProject,
            'project' => $this->project,
        ]);
    }
}
