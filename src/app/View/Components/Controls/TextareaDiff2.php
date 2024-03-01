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
        $value = $this->value;
        $value2 = $this->value2;
        $isModeDraft = $this->modeDraft();
        return view('components.controls.textarea-diff2', [
            'name' => $this->name,
            'mode' => $this->mode,
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
    
}
