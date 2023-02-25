<?php

namespace App\View\Components\Controls\HasDataSource;

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
        $params = [
            'name' => $name,
            'id' => $id,
            'selected' => $this->selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'className' => "bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white",
            'table' => $table,
            'readOnly' => $this->readOnly,
        ];
        // dump($params);
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
