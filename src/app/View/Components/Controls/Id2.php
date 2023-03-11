<?php

namespace App\View\Components\Controls;

use App\Utils\ClassList;
use Illuminate\View\Component;

class Id2 extends Component
{
    public function __construct(
        private $name,
        private $value,
    ) {
    }

    public function render()
    {
        return view('components.controls.id2', [
            'name' => $this->name,
            'value' => $this->value,
            'classList' => ClassList::TEXT,
        ]);
    }
}
