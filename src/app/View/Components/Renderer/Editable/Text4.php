<?php

namespace App\View\Components\Renderer\Editable;

use App\Utils\ClassList;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class Text4 extends Component
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
        private $batchLength = 1,
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
        return view('components.renderer.editable.text4', [
            'placeholder' => $this->placeholder,
            'name' => $this->name,
            'cell' => $this->cell,
            'onChange' => $this->onChange,
            'rowIndex' => $this->rowIndex,
            'table01Name' => $this->table01Name,
            'icon' => $this->icon,
            'saveOnChange' => $this->saveOnChange,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::TEXT,
            'batchLength' => $this->batchLength,
        ]);
    }
}
