<?php

namespace App\View\Components\Renderer\Editable;

use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = '',
        private $cell = '',
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->cell === 'invisible') return "";
        return view('components.renderer.editable.checkbox', [
            'name' => $this->name,
            'value' => in_array($this->cell, [1, 'true']),
            'disabled' => $this->cell === 'disabled',
            // 'cell' => $this->cell,
            // 'invisible' => ($this->cell === 'invisible') ? "invisible" : "",
        ]);
    }
}
