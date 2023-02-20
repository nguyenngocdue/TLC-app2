<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class DatePicker3 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $value,
        private $control,
    ) {
        //
    }
    private function getPlaceholder($control)
    {
        switch ($control) {
            case "picker_datetime":
                return "DD/MM/YYYY HH:MM:SS";
            case "picker_time":
                return "HH:MM:SS";
            case "picker_date":
            case "picker_month":
            case "picker_week":
            case "picker_quarter":
            case "picker_year":
                return "DD/MM/YYYY";
            default:
                return "??? $control ???";
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $name = $this->name;
        $value = $this->value;
        $control = $this->control;
        $placeholder = $this->getPlaceholder($control);
        return view('components.controls.date-picker3', [
            'name' => $name,
            'value' => $value,
            'placeholder' => $placeholder
        ]);
    }
}
