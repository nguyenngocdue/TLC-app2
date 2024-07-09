<?php

namespace App\View\Components\Controls;

use App\Utils\ClassList;
use App\Utils\Support\Entities;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class EntityTypeDropdown2 extends Component
{
    // private $value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $modelPath,
        private $value = null,
        private $readOnly = false,
    ) {
        //
    }

    public function render()
    {
        $all = [
            '',
            ...Entities::getAllPluralNames(),
        ];
        return view("components.controls.entity-type-dropdown2", [
            'options' => $all,
            'name' => $this->name,
            'value' => $this->value,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
        ]);
    }
}
