<?php

namespace App\View\Components\Controls;

use App\Utils\ClassList;
use Illuminate\View\Component;

class Text2 extends Component
{

    public function __construct(
        private $name,
        private $value,
        private $class = '',
        private $component = 'controls/text2',
        private $placeholder = 'Type here...',
        private $icon = null,
        private $readOnly = false,
        private $onkeypress = '',
    ) {
    }

    public function render()
    {
        $name = $this->name;
        $value = $this->value;
        $placeholder = $this->placeholder;
        $icon = $this->icon;
        $readOnly = $this->readOnly;
        return view('components.controls.text2', [
            'name' => $name,
            'value' => $value,
            'class' => $this->class,
            'component' => $this->component,
            'placeholder' => $placeholder,
            'icon' => $icon,
            'readOnly' => $readOnly,
            'classList' => ClassList::TEXT,
            'onkeypress' => $this->onkeypress,
        ]);
    }
}
