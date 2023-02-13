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
        private $itemsSelected = [],
        private $controlNames = [],
        private $controlValues = [],
    ) {
        //
    }

    public function render()
    {
        $controlNames = $this->controlNames;
        $controlValues = $this->controlValues;
        $dataRender = array_combine($controlNames, $controlValues);
        return view('components.renderer.modes-report', [
            'itemsSelected' => $this->itemsSelected,
            'dataRender' => $dataRender,
        ]);
    }
}
