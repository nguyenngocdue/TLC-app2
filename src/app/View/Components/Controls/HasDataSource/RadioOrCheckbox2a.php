<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Utils\ClassList;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class RadioOrCheckbox2a extends Component
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
        private $action = null,
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    public function render()
    {
        $sp = SuperProps::getFor($this->type);
        $prop = $sp['props']["_" . $this->name];
        $table = $prop['relationships']['table'];
        $span = $prop['relationships']['radio_checkbox_colspan'] ? $prop['relationships']['radio_checkbox_colspan'] : 4;
        $letUserChooseWhenOneItem = ($prop['relationships']['let_user_choose_when_one_item'] ?? false) === "true";
        // dump($prop['relationships']);
        $id = $this->name;
        $name =  $this->name;
        $params = [
            'name' => $name,
            'id' => $id,
            'selected' => $this->selected,
            'multiple' => $this->multiple ? true : false,
            'classList' => ClassList::RADIO_CHECKBOX . ($this->readOnly ? ' readonly ' : ''),
            'table' => $table,
            'span' => $span,
            'readOnly' => $this->readOnly,
            'saveOnChange' => $this->saveOnChange,
            'action' => $this->action,
            'letUserChooseWhenOneItem' => $letUserChooseWhenOneItem,
        ];

        return view('components.controls.has-data-source.radio-or-checkbox2a', $params);
    }
}
