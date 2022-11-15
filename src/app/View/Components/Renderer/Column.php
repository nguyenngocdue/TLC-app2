<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Column extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $rendererParam = '', private $name = '')
    {
        // dd($this->renderer_param);
        // dump($this->rendererParam);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // return $this->renderer_param;
        if ($this->rendererParam === '') return "renderer_param ?";
        return view('components.renderer.column', [
            'rendererParam' => $this->rendererParam,
        ]);
    }
}
