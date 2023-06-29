<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Utils\ClassList;
use Illuminate\View\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Dropdown4 extends Component
{
    // use HasDataSource;
    public function __construct(
        private $name,
        private $selected = null,
        private $multiple = false,
        private $type = null,
        private $readOnly = false,
        private $saveOnChange = false,

        private $table01Name = null,
        private $tableName = null,
        private $lineType = null,
        private $rowIndex = null,
        private $batchLength = 1,
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    public function render()
    {
        $id = $this->name;
        // dump($id);
        $name = $this->multiple ? $this->name . "[]" : $this->name;
        $table = $this->tableName;
        $params = [
            'name' => $name,
            'id' => $id,
            'selected' => $this->selected,
            'multiple' => $this->multiple,
            'classList' => ClassList::DROPDOWN,
            'table' => $table,
            'readOnly' => $this->readOnly,
            'saveOnChange' => $this->saveOnChange,

            'lineType' => $this->lineType,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'batchLength' => $this->batchLength,
        ];
        // dump($params);
        return view('components.controls.has-data-source.dropdown4', $params);
    }
}
