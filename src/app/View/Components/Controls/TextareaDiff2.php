<?php

namespace App\View\Components\Controls;

use App\Utils\ClassList;
use Illuminate\View\Component;

class TextareaDiff2 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $value,
        private $value2,
        private $colType = '',
        private $placeholder = "Type here...",
        private $mode = 'normal',
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {   
        $value = $this->prettyValueTextarea($this->value);
        $value2 = $this->prettyValueTextarea($this->value2);
        $isModeDraft = $this->modeDraft();
        return view('components.controls.textarea-diff2', [
            'name' => $this->name,
            'value' => $isModeDraft ? $value2 : $value,
            'value2' => $isModeDraft ?$value : $value2,
            'isModeDraft' => $isModeDraft,
            'classList' => ClassList::TEXTAREA,
            'placeholder' => $this->placeholder
        ]);
    }
    private function modeDraft(){
        return $this->mode == 'draft';
    }
    private function prettyValueTextarea($value){
        $arrContent = gettype($value) === "array" ? $value : json_decode($value, true);
        $tmp = $this->colType === 'json' ? json_encode($arrContent, JSON_PRETTY_PRINT) : $value;
        $value = $tmp === 'null' ? "" : $tmp;
        return $value;
    }
}
