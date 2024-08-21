<?php

namespace App\View\Components\Reports2;

use App\View\Components\Reports2\Charts\TraitTransformationData;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ReportBlockChart extends Component
{
    use TraitDataColumnReport;
    use TraitTransformationData;

    public function __construct(
        private $block = null,
        private $queriedData = null,
        private $rawTableColumns = []
    ) {}

    private function formatJsonString($longString)
    {
        // Add double quotes around keys
        $jsonString = preg_replace('#(\w+):#', '"$1":', $longString);

        // Remove comments (both single-line and multi-line)
        // This ensures the JSON string is valid and can be parsed without errors.
        $jsonString = preg_replace('#//.*|/\*[\s\S]*?\*/#', '', $jsonString);
        $jsonChart = json_decode($jsonString, 1);
        $jsonChart = json_encode($jsonChart, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $jsonString;
    }

    public function render()
    {
        $block = $this->block;
        $chartTypeId = $block->chart_type;
        $jsonChart = $this->formatJsonString($block->chart_json);

        $queriedData = $this->queriedData;
        $viewName = '';
        $series = [];
        $key = hash('sha256', $chartTypeId . $block->name);;
        $tableColumns = $this->rawTableColumns;


        switch ($chartTypeId) {
            case (684):
            case (681):
                $typeOfTrans = "rows_to_fields";
                switch ($typeOfTrans) {
                    case 'rows__to_fields':
                        $transformation = $this->makeRowsToFields($queriedData, $tableColumns);
                        break;
                    default:
                        $transformation = $this->groupNames($queriedData);
                        break;
                }
                $series = $this->makeSeriesChart($transformation);
                $viewName = 'chart-bar';
                break;
            case (682):
                $viewName = 'chart-pie-donut';
                break;
            case (683):
                $viewName = 'chart-column';
                break;
        }
        // dump($jsonChart);

        if ($viewName) {
            $componentName = "x-reports2.charts.types." . $viewName;
            $chart = '<' . $componentName . ' key="{{$key}}" chartTypeId="{{$chartTypeId}}" :tableColumns="$tableColumns" :series="$series" :jsonChart="$jsonChart" :queriedData="$queriedData"/>';

            return  Blade::render($chart, [
                'key' => $key,
                'block' => $block,
                'chartTypeId' => $chartTypeId,
                'jsonChart' => $jsonChart,
                'queriedData' => $queriedData,
                'series' => $series,
                'tableColumns' => $tableColumns,
            ]);
        }
    }
}
