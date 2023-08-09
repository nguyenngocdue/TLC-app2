<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Popover extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $id = null, 
    private $title = null,
     private $content = null,
     private $hidden = false,)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.renderer.popover', [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'hidden' => $this->hidden,
        ]);
    }
}
