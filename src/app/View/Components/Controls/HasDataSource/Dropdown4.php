<?php

namespace App\View\Components\Controls\HasDataSource;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class Dropdown4 extends Component
{
    // use HasDataSource;
    public function __construct(
        private $name,
        private $selected = null,
        private $multiple = false,
        private $type = null,

        private $table01Name = null,
        private $tableName = null,
        private $lineType = null,
        private $rowIndex = null,
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
        $id = $this->name;
        // dump($id);
        $name = $this->multiple ? $this->name . "[]" : $this->name;
        $table = $this->tableName;
        $params = [
            'name' => $name,
            'id' => $id,
            'selected' => $this->selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'className' => "bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white",
            'table' => $table,

            'lineType' => $this->lineType,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
        ];
        // dump($params);
        return view('components.controls.has-data-source.dropdown4', $params);
    }
}
