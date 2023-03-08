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
        if ($this->cell === 'DO_NOT_RENDER') return "";
        // dump($this->onChange);
        $onChange = $this->onChange ?? "onChangeDropdown4({name:'{$this->name}',table01Name:'{$this->table01Name}',rowIndex:{$this->rowIndex},saveOnChange:" . ($this->saveOnChange ? 1 : 0) . ")";
        return view('components.renderer.editable.number', [
            'placeholder' => $this->placeholder,
            'name' => $this->name,
            'onChange' => $this->onChange,
            'icon' => $this->icon,
            'readOnly' => $this->readOnly,
            'onChange' => $onChange,
        ]);
    }
}
