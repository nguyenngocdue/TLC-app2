<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;

class RadioOrCheckBox extends Component
{
    // use HasDataSource;
    public function __construct(
        private $type,
        private $name,
        private $selected,
        private $table01Name = null,
        private $multiple = false,
        private $readOnly = false,
        private $saveOnChange = false,
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
        $table = $prop['relationships']['table'];
        $span = $prop['relationships']['radio_checkbox_colspan'] ? $prop['relationships']['radio_checkbox_colspan'] : 4;
        // dump($prop['relationships']);
        $id = $this->name;
        $name =  $this->name;
        $params = [
            'name' => $name,
            'id' => $id,
            'selected' => $this->selected,
            'multiple' => $this->multiple ? "true" : "false",
            'className' => "grid grid-cols-12 gap-2 bg-white border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500  w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500",
            'table' => $table,
            'span' => $span,
            'readOnly' => $this->readOnly,
            'saveOnChange' => $this->saveOnChange,
        ];
        // dump($params);

        return view('components.controls.has-data-source.radio-or-checkbox', $params);
    }
}
