<?php

namespace App\View\Components\Renderer;

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
        $timestamp = strtotime($rawData);
        switch ($this->rendererParam) {
            case "picker_datetime":
                return date("d/m/Y H:i:s", $timestamp);
            case "picker_date":
                return date("d/m/Y", $timestamp);
            case "picker_time":
                return date("H:i:s", $timestamp);
            case "picker_month":
                return date("m/Y", $timestamp);
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
