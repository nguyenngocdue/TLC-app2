<?php

namespace App\View\Components\Controls;

use App\Utils\Constant;
use Carbon\Carbon;
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
        private $value = null,
        private $dateTimeType = 'picker_date',
    ) {
        if (!is_null(old($name))) $this->value = old($name);
    }
    private function getPlaceholder($control)
    {
        switch ($control) {
            case "picker_datetime":
                return "DD/MM/YYYY HH:MM";
            case "picker_time":
                return "HH:MM";
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
        // dump($this->dateTimeType);
        // dump($this->value);


        return view(
            'components.controls.date-picker3',
            [
                // 'placeholder' => $this->getPlaceholder($this->dateTimeType),
                'name' => $this->name,
                'value' => $this->value,
            ]
        );
    }
}
