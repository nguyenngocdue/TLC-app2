<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class ModesReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataControl1 = [],
        private $dataControl2 = [],
        private $itemsSelected = [],
        private $nameControl1 = "",
        private $nameControl2 = "",
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
        return view('components.renderer.modes-report', [
            'dataControl1' => $this->dataControl1,
            'dataControl2' => $this->dataControl2,
            'itemsSelected' => $this->itemsSelected,
            'nameControl1' => $this->nameControl1,
            'nameControl2' => $this->nameControl2,
        ]);
    }
}
