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
        private $dataSource
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

        $propTree = $this->propTree;
        $dataSource = $this->dataSource;
        return view('components.renderer.description-group', [
            'propTree' => $propTree,
            'dataSource' => $dataSource
        ]);
    }
}
