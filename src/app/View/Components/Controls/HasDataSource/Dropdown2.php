<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Utils\ClassList;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Dropdown2 extends Component
{
    // use HasDataSource;
    public function __construct(
        private $name,
        private $selected = null,
        private $multiple = false,
        private $type = null,
        private $readOnly = false,
        private $saveOnChange = false,
        private $allowClear = false,
    ) {
        $old = old($name);
        if ($old) {
            $this->selected = (is_array($old)) ? "[" . join(",", $old) . "]" : "[$old]";
        } else {
            if (isset($this->selected[0])) {
                $this->selected =  ($this->selected[0] != '[') ? "[" . $this->selected . "]" : $this->selected;
            } else {
                $this->selected = "[]";
            }
        }
        // dump($this->selected);
    }

    public function render()
    {
        $sp = SuperProps::getFor($this->type);
        $prop = $sp['props']["_" . $this->name];
        if (!isset($prop['relationships']['table'])) {
            dump("Orphan prop " . $this->type . "\\" . $this->name);
            return;
        }
        $table = $prop['relationships']['table'];
        $id = $this->name;
        $name = $this->multiple ? $this->name . "[]" : $this->name;

        $nameless = (new (Str::modelPathFrom($table)))->nameless;

        $params = [
            'name' => $name,
            'id' => $id,
            'selected' => $this->selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'classList' => ClassList::DROPDOWN,
            'table' => $table,
            'readOnly' => $this->readOnly,
            'saveOnChange' => $this->saveOnChange,
            'allowClear' => $this->allowClear,
            'nameless' => $nameless,
        ];
        // dump($params);
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
