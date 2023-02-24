<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Realtime extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = null,
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
        echo $this->name;
        // return view('components.renderer.realtime', [
        //     'name' => $this->name,
        // ]);
    }
}
