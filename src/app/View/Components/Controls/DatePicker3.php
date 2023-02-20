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
        // dump($this->dateTimeType);
        // dump($this->value);
        switch ($this->dateTimeType) {
            case 'picker_datetime':

                $value = date(Constant::FORMAT_DATETIME_ASIAN, strtotime($this->value));
                $this->value = $value;
                break;
            case 'picker_date':
                $this->value = Carbon::createFromFormat(Constant::FORMAT_DATE_MYSQL, $this->value)
                    ->format(Constant::FORMAT_DATE_ASIAN);
                break;
            default:
                return "Unknown control " . $this->dateTimeType;
                break;
        }

        return view(
            'components.controls.date-picker3',
            [
                'name' => $this->name,
                'value' => $this->value,
            ]
        );
    }
}
