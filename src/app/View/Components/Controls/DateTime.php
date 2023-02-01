<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;

class DateTime extends Component
{
    public function __construct(
        private $name,
        private $value,
        private $control,
    ) {
    }

    private function getPlaceholder($control)
    {
        switch ($control) {
            case "picker_datetime":
                return "YYYY-MM-DD HH:MM:SS";
            case "picker_time":
                return "HH:MM:SS";
            case "picker_date":
            case "picker_month":
            case "picker_week":
            case "picker_quarter":
            case "picker_year":
                return "YYYY-MM-DD";
            default:
                return "??? $control ???";
        }
    }

    public function render()
    {
        $name = $this->name;
        $value = $this->value;
        $control = $this->control;
        $placeholder = $this->getPlaceholder($control);
        return view('components.controls.text')->with(compact('name', 'value', 'placeholder'));
    }
}
