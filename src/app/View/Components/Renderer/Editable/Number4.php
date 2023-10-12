<?php

namespace App\View\Components\Renderer\Editable;

use App\Utils\ClassList;
use App\Utils\ColorList;
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
        private $numericScale = 0,
        private $table01Name = 'table00',
        private $rowIndex = -1,
        private $icon = null,
        private $saveOnChange = false,
        private $readOnly = false,
        private $style = "",
    ) {
    }

    function getBgColor()
    {
        $fieldName = Str::getFieldNameInTable01Format($this->name, $this->table01Name);
        $value = $this->cell;
        if (in_array($fieldName, ['month_remaining_hours'])) {
            return ColorList::getBgColorForRemainingOTHours($value, 40);
        }
        if (in_array($fieldName, ['year_remaining_hours'])) {
            return ColorList::getBgColorForRemainingOTHours($value, 200);
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
        if (str_starts_with($this->cell, 'No dataIndex for ')) $this->cell = 0;
        // dump($this->onChange);
        // $onChange = $this->onChange ?? "onChangeDropdown4({name:'{$this->name}',table01Name:'{$this->table01Name}',rowIndex:{$this->rowIndex},saveOnChange:" . ($this->saveOnChange ? 1 : 0) . "})";
        $bgColor = $this->getBgColor();
        // dump($bgColor);
        return view('components.renderer.editable.number4', [
            'placeholder' => $this->placeholder,
            'name' => $this->name,
            'onChange' => $this->onChange,
            'icon' => $this->icon,
            'readOnly' => $this->readOnly,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'saveOnChange' => $this->saveOnChange,
            'numericScale' => $this->numericScale,
            'bgColor' => $bgColor,
            'classList' => ClassList::TEXT . " text-right",
            'onChange' => $this->onChange,
            'style' => $this->style,
        ]);
    }
}
