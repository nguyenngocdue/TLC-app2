<?php

namespace App\View\Components\Controls;

use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class Text extends Component
{

    public function __construct(
        private $name,
        private $value,
        private $placeholder = 'Type here...',
        private $icon = null,
        private $readOnly = false,
    ) {
    }

    public function render()
    {
        $name = $this->name;
        $value = $this->value;
        $placeholder = $this->placeholder;
        $icon = $this->icon;
        $readOnly = $this->readOnly;
        return view('components.controls.text', [
            'name' => $name,
            'value' => $value,
            'placeholder' => $placeholder,
            'icon' => $icon,
            'readOnly' => $readOnly,
            'classList' => ClassList::TEXT,
        ]);
    }
}
