<?php

namespace App\View\Components\Controls;

use App\Utils\ClassList;
use Illuminate\View\Component;

class TextEditor extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $value,
        private $isReadOnly = true,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.controls.text-editor',
            [
                'name' => $this->name,
                'value' => $this->value,
                'isReadOnly' => $this->isReadOnly,
                'classList' => ClassList::TEXTAREA,
            ]
        );
    }
}
