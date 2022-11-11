<?php

namespace App\View\Components\Renderer\Editable;

use Illuminate\View\Component;

class ReadOnlyText extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $name = "", private $placeholder = "")
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.renderer.editable.text', [
            'type' => "hidden",
            "name" => $this->name,
            "placeholder" => $this->placeholder,
        ]);
    }
}
