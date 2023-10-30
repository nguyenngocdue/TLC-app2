<?php

namespace App\View\Components\Controls;

use App\Utils\ClassList;
use Illuminate\View\Component;

class Textarea2 extends Component
{
    public function __construct(
        private $name,
        private $value,
        private $colType = '',
        private $readOnly = false,
        private $textareaRows = 5,
    ) {
        if (!$textareaRows) $this->textareaRows = 5;
        // dump($textareaRows);
    }

    public function render()
    {
        $name = $this->name;

        $arrContent = gettype($this->value) === "array" ? $this->value : json_decode($this->value, true);
        $_value = $this->colType === 'json' ? json_encode($arrContent, JSON_PRETTY_PRINT) : $this->value;
        $value = $_value === 'null' ? "" : $_value;


        return view('components.controls.textarea2', [
            'name' => $name,
            'value' => $value,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::TEXTAREA,
            'rows' => $this->textareaRows,
        ]);
    }
}
