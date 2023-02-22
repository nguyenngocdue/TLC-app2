<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Comment2 extends Component
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
        return view('components.renderer.comment2', [
            'name' => $this->name,
        ]);
    }
}
