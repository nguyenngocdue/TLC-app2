<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Utils\ClassList;
use App\Utils\Support\Json\SuperProps;
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
        private $deaf = false,
        private $fieldName = null,
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    public function render()
    {
        $id = $this->name;
        // dump($id);
        $name = $this->multiple ? $this->name . "[]" : $this->name;
        $table = $this->tableName;
        $nameless = Str::modelPathFrom($table)::$nameless;

        // dump($this->lineType);
        $sp = SuperProps::getFor($this->lineType);
        $props = $sp['props'];
        $prop = $props["_" . $this->fieldName];

        [
            'let_user_open' => $let_user_open,
            'let_user_clear' => $let_user_clear,
            'let_user_choose_when_one_item' => $let_user_choose_when_one_item,
        ] = $prop['relationships'];

        $params = [
            'name' => $name,
            'id' => $id,
            'selected' => $this->selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'readOnlyStr' => $this->readOnly ? "readonly" : "",

            'classList' => ClassList::DROPDOWN,
            'table' => $table,
            'saveOnChange' => $this->saveOnChange,

            'lineType' => $this->lineType,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'batchLength' => $this->batchLength,
            'nameless' => $nameless,
            'deaf' => $this->deaf,

            'let_user_open' => !!$let_user_open,
            'let_user_clear' => !!$let_user_clear,
            'let_user_choose_when_one_item' => !! $let_user_choose_when_one_item,
        ];
        // dump($params);
        return view('components.controls.has-data-source.dropdown4', $params);
    }
}
