<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class DescriptionGroup extends Component
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

        return view('components.renderer.description-group', [
            'propTree' => $this->propTree,
            'dataSource' => $this->dataSource,
            'type' => $this->type,
            'modelPath' => $this->modelPath
        ]);
    }
}
