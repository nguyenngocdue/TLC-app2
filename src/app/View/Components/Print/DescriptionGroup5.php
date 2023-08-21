<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class DescriptionGroup5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $propTree,
        private $dataSource,
        private $type,
        private $modelPath,
        private $numberOfEmptyLines = 0,
        private $printMode = null,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.print.description-group5', [
            'propTree' => $this->propTree,
            'dataSource' => $this->dataSource,
            'type' => $this->type,
            'modelPath' => $this->modelPath,
            'numberOfEmptyLines' => $this->numberOfEmptyLines,
            'printMode' => $this->printMode,
        ]);
    }
}
