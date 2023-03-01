<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class ParameterControl extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $itemsSelected = [],
        private $dataSource = [],
        private $hiddenItems = [],
    ) {
        //
    }

    public function render()
    {
        // dd($this->dataSource);
        return view('components.renderer.parameter-control', [
            'itemsSelected' => $this->itemsSelected,
            'dataSource' => $this->dataSource,
            'hiddenItems' => $this->hiddenItems,
        ]);
    }
}
