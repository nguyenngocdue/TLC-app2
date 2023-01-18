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
        return view('components.renderer.editable.checkbox', [
            'name' => $this->name,
            'value' => $this->cell === 'true',
        ]);
    }
}
