<?php

namespace App\View\Components\Renderer\Editable;

use App\Utils\ClassList;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Number4 extends Component
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
        private $table01Name = 'table00',
        private $rowIndex = -1,
        private $icon = null,
        private $saveOnChange = false,
        private $readOnly = false,
    ) {
    }

    function getBgColor()
    {
        $fieldName = Str::getFieldNameInTable01Format($this->name, $this->table01Name);
        if (in_array($fieldName, ['remaining_hours', 'allowed_hours_111'])) {
            $value = $this->cell;
            // dump($this->cell);
            switch (true) {
                case $value < 0:
                    return 'bg-red-600';
                case $value <= 10:
                    return 'bg-pink-400';
                case $value <= 20:
                    return 'bg-orange-300';
                case $value <= 30:
                    return 'bg-yellow-300';
                case $value <= 40:
                    return 'bg-green-300';
            }
        }
        return ($this->readOnly)  ? "readonly" : "";
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
        $onChange = $this->onChange ?? "onChangeDropdown4({name:'{$this->name}',table01Name:'{$this->table01Name}',rowIndex:{$this->rowIndex},saveOnChange:" . ($this->saveOnChange ? 1 : 0) . "})";
        $bgColor = $this->getBgColor();
        // dump($bgColor);
        return view('components.renderer.editable.number4', [
            'placeholder' => $this->placeholder,
            'name' => $this->name,
            'onChange' => $this->onChange,
            'icon' => $this->icon,
            'readOnly' => $this->readOnly,
            'table01Name' => $this->table01Name,
            'onChange' => $onChange,
            'bgColor' => $bgColor,
            'classList' => ClassList::TEXT . " text-right",
        ]);
    }
}
