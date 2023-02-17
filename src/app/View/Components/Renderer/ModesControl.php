<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class ModesControl extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $itemsSelected = [],
        private $dataSource = [],
        private $hiddenItems = []
    ) {
        //
    }

    public function render()
    {
        return view('components.renderer.modes-control', [
            'itemsSelected' => $this->itemsSelected,
            'dataSource' => $this->dataSource,
            'hiddenItems' => $this->hiddenItems
        ]);
    }
}
