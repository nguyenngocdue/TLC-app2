<?php

namespace App\View\Components\Renderer\Editable;

use App\Utils\ClassList;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class ReadOnlyText4 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = "",
        private $placeholder = "",
        private $cell = '',
        private $align = 'left',
        private $table01Name = null,
        private $rowIndex = -1,
        private $saveOnChange = false,
        private $column = null,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (is_array($this->cell)) {
            $value = $this->cell['value'];
            $title = $this->cell['title'];
        } else {
            $value = $this->cell;
            $title = $this->cell;
        }
        if (str_starts_with($value, "No dataIndex for ")) $value = null;
        if (str_starts_with($title, "No dataIndex for ")) $title = null;
        $href = (isset($this->column['properties']['lineTypeRoute'])) ? $this->column['properties']['lineTypeRoute'] . $title . "/edit" : "";
        $isId = ($this->column['dataIndex'] ?? false) == 'id';
        return view('components.renderer.editable.read-only-text4', [
            'type' => "hidden",
            "name" => $this->name,
            "placeholder" => $this->placeholder,
            'value' => $value,
            'title' => $title,
            'align' => $this->align,
            'classList' => ClassList::TEXT,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'saveOnChange' => $this->saveOnChange,
            'href' => $href,
            'isId' => $isId,
        ]);
    }
}
