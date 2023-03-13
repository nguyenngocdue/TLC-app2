<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Utils\ClassList;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;

class RadioOrCheckbox2 extends Component
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
            'multiple' => $this->multiple ? true : false,
            'classList' => ClassList::RADIO_CHECKBOX,
            'table' => $table,
            'span' => $span,
            'readOnly' => $this->readOnly,
            'saveOnChange' => $this->saveOnChange,
        ];

        return view('components.controls.has-data-source.radio-or-checkbox2', $params);
    }
}
