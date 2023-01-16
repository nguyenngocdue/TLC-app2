<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Descriptions extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $items,
        private $dataContent,
        private $control
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

        $items = $this->items;
        $dataContent = $this->dataContent;
        $control = $this->control;
        return view('components.renderer.descriptions')->with(compact('items', 'dataContent', 'control'));
    }
}
