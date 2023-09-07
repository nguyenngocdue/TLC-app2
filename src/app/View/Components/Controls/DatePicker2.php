<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class DatePicker2 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $value = null,
        private $dateTimeType = 'picker_date',
        private $readOnly = false,
    ) {
        if (!is_null(old($name))) $this->value = old($name);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // dump($this->dateTimeType);
        // dump($this->value);
        echo "(predicated2)";

        return view(
            'components.controls.date-picker2',
            [
                'name' => $this->name,
                'value' => $this->value,
                'readOnly' => $this->readOnly,
                'control' => $this->dateTimeType,
            ]
        );
    }
}
