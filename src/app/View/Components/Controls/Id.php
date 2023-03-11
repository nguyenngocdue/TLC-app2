<?php

namespace App\View\Components\Controls;

use App\Utils\ClassList;
use Illuminate\View\Component;

class Id extends Component
{
    public function __construct(
        private $name,
        private $value,
    ) {
    }

    public function render()
    {
        return view('components.controls.id', [
            'name' => $this->name,
            'value' => $this->value,
            'classList' => ClassList::TEXT,
        ]);
    }
}
