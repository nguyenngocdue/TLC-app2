<?php

namespace App\View\Components\Renderer\Editable;

use App\Utils\ClassList;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class Textarea4 extends Component
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
        private $readOnly = false,
    ) {
        //In case of listeners, the data was parsed in to array
        if (is_array($this->cell)) {
            // dd($this->cell);
            $this->cell = join(",", $this->cell);
        }
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
        if ($this->cell === 'DO_NOT_RENDER') return "";
        return view('components.renderer.editable.textarea4', [
            'placeholder' => $this->placeholder,
            'name' => $this->name,
            'type' => 'text',
            'cell' => $this->cell,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::TEXTAREA,
        ]);
    }
}
