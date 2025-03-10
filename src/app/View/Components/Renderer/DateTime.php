<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\DateTimeConcern;
use Illuminate\View\Component;

class DateTime extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $column,
        private $dataLine,
        private $rendererParam = 'picker_datetime',
    ) {
        // Log::info($column);
        // Log::info($rendererParam);
        // Log::info($dataLine);
    }

    // private function toString($timestamp)
    // {
    //     return DateTimeConcern::convertForLoading($this->rendererParam, $timestamp);
    //     switch ($this->rendererParam) {
    //         case "picker_datetime":
    //             return date(Constant::FORMAT_DATETIME_ASIAN, $timestamp);
    //         case "picker_date":
    //             return date(Constant::FORMAT_DATE_ASIAN, $timestamp);
    //         case "picker_time":
    //             return date(Constant::FORMAT_TIME, $timestamp);
    //         case "picker_month":
    //             return date(Constant::FORMAT_MONTH, $timestamp);
    //         case "picker_week":
    //             return "W" . date(Constant::FORMAT_WEEK, $timestamp);
    //         case "picker_quarter":
    //             return "Q" . ceil(date("m", $timestamp) / 3) . "/" . date("Y", $timestamp);
    //         case "picker_year":
    //             return date("Y", $timestamp);
    //         default:
    //             return "Unknown type [{$this->rendererParam}]";
    //             break;
    //     }
    // }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataIndex = $this->column['dataIndex'];
        $rawData = $this->dataLine->{$dataIndex};
        if (is_null($rawData)) return ""; //<< This to render empty string on ViewAll screen
        // $timestamp = strtotime($rawData);
        // $string = $this->toString($timestamp);
        $string = DateTimeConcern::convertForLoading($this->rendererParam, $rawData);
        return "<p class='p-2'>$string</p>";
    }
}
