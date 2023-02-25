<?php

namespace App\View\Components\Renderer\Editable;

use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class Number extends Component
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
        private $onChange = null,
        private $table01Name = null,
        private $rowIndex = -1,
        private $icon = null,
        private $saveOnChange = false,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->cell === 'DO_NOT_RENDER') return "";
        return view('components.renderer.editable.number', [
            'placeholder' => $this->placeholder,
            'name' => $this->name,
            'onChange' => $this->onChange,
            'rowIndex' => $this->rowIndex,
            'table01Name' => $this->table01Name,
            'icon' => $this->icon,
            'saveOnChange' => $this->saveOnChange,
        ]);
    }
}
