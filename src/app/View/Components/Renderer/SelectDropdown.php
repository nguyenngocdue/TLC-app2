<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class SelectDropdown extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id,
        private $dataSource,
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
        return view(
            'components.renderer.select-dropdown',
            [
                'id' => $this->id,
                'dataSource' => $this->dataSource,
            ]
        );
    }
}
