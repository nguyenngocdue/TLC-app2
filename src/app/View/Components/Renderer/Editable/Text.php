<?php

namespace App\View\Components\Renderer\Editable;

use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class Text extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = "",
        private $placeholder = "",
        private $cell = null,
        // private $width = null,
    ) {
        // $this->cell = "hello \"a\"";
        // dump($this->cell);
        if (str_starts_with($this->cell, "No dataIndex for ")) {
            $this->cell = "";
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->cell === 'invisible_this_control') return "";
        return view('components.renderer.editable.text', [
            'placeholder' => $this->placeholder,
            'name' => $this->name,
            'type' => 'text',
            'cell' => $this->cell,
            // 'width' => $this->width,
        ]);
    }
}
