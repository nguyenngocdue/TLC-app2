<?php

namespace App\View\Components\Reports2;

use App\View\Components\Reports2\Charts\TraitTransformationData;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ChartBlockReport extends Component
{
    use TraitDataColumnReport;
    use TraitTransformationData;

    public function __construct(
        private $block = null,
        private $dataQuery = null,
        private $tableColumns = []
    ) {
    }

    public function render()
    {
        $block = $this->block;
        $chartType = $block->chart_type;
        $chartJson = $block->chart_json;
        $dataQuery = $this->dataQuery;
        $viewName = '';
        $series = [];
        switch ($chartType) {
            case (681):
                $typeOfTrans = "rows_to_fields";
                switch ($typeOfTrans) {
                    case 'rows__to_fields':
                        $transformation = $this->makeRowsToFields($dataQuery, $this->tableColumns);
                        break;
                    default:
                        $transformation = $this->mapDataQueryToColumns($dataQuery, $this->tableColumns);
                        break;
                }
                $series = $this->makeSeriesChart($transformation, $this->tableColumns);

                $chartJson = json_decode($chartJson);
                $chartJson = array_pop($chartJson);
                $viewName = 'chart-bar';
                break;
            case (682):
                $viewName = 'chart-column-1';
                break;
            case (683):
                $viewName = 'chart-column-2';
                break;
            case (684):
                $viewName = 'chart-column-3';
                break;
        }

        if ($viewName) {
            $name = "<x-renderer.heading level=5 xalign='left'>{$block->name}</x-renderer.heading>";
            $description = "<x-renderer.heading level=6 xalign='left'>{$block->description}</x-renderer.heading>";
            $componentName = "x-reports2.charts.types." . $viewName;
            $chart = '<' . $componentName . ' chartType="{{$chartType}}" :series="$series" :chartJson="$chartJson" :dataQuery="$dataQuery"/>';
            $views = $name . $description . $chart;
            return  Blade::render($views, [
                'chartType' => $chartType,
                'chartJson' => $chartJson,
                'dataQuery' => $dataQuery,
                'series' => $series,
            ]);
        }
    }
}
