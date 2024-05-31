<?php

namespace App\View\Components\Renderer\Editable;

use App\Utils\ClassList;
use Illuminate\View\Component;

class Toggle4 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = "",
        private $cell = null,
        private $readOnly = false,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->cell = str_starts_with($this->cell, "No dataIndex for ") ? 0 : $this->cell;
        if ($this->cell === 'DO_NOT_RENDER') return "";
        return view('components.renderer.editable.toggle4', [
            'name' => $this->name,
            'value' => $this->cell,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::TOGGLE,
        ]);
    }
}
