<?php

namespace App\View\Components\Renderer;

use App\Utils\Constant;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class DateTime extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $column, private $rendererParam, private $dataLine)
    {
        // Log::info($column);
        // Log::info($rendererParam);
        // Log::info($dataLine);
    }

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
        $timestamp = strtotime($rawData);

        switch ($this->rendererParam) {
            case "picker_datetime":
                return date(Constant::FORMAT_DATETIME_ASIAN, $timestamp);
            case "picker_date":
                return date(Constant::FORMAT_DATE_ASIAN, $timestamp);
            case "picker_time":
                return date(Constant::FORMAT_TIME, $timestamp);
            case "picker_month":
                return date(Constant::FORMAT_YEAR_MONTH0, $timestamp);
            case "picker_week":
                return "W" . date("W-Y", $timestamp);
            case "picker_quarter":
                return "Q" . ceil(date("m", $timestamp) / 3) . "-" . date("Y", $timestamp);
            case "picker_year":
                return date("Y", $timestamp);
            default:
                return "Unknown type [{$this->rendererParam}]";
                break;
        }
    }
}
