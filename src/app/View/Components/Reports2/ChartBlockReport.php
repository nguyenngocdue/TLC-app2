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
        private $rawTableColumns = []
    ) {
    }

    public function render()
    {
        $block = $this->block;
        $chartTypeId = $block->chart_type;
        $chartJson = $block->chart_json;
        $dataQuery = $this->dataQuery;
        $viewName = '';
        $series = [];
        $key = md5($chartTypeId);
        $tableColumns = $this->rawTableColumns;


        switch ($chartTypeId) {
            case (681):
                $typeOfTrans = "rows_to_fields";
                switch ($typeOfTrans) {
                    case 'rows__to_fields':
                        $transformation = $this->makeRowsToFields($dataQuery, $tableColumns);
                        break;
                    default:
                        $transformation = $this->mapDataQueryToColumns($dataQuery, $tableColumns);
                        break;
                }
                $series = $this->makeSeriesChart($transformation, $tableColumns);

                $chartJson = json_decode($chartJson);
                $chartJson = array_pop($chartJson);
                $viewName = 'chart-bar';
                break;
            case (682):
                $viewName = 'chart-pie-donut';
                break;
            case (683):
                $viewName = 'chart-column';
                break;
        }


        if ($viewName) {
            $titleAndDesc = '<x-renderer.report2.title-description-block :block="$block" />';
            $componentName = "x-reports2.charts.types." . $viewName;
            $chart = '<' . $componentName . ' key="{{$key}}" chartTypeId="{{$chartTypeId}}" :tableColumns="$tableColumns" :series="$series" :chartJson="$chartJson" :dataQuery="$dataQuery"/>';
            $views = $titleAndDesc . $chart;

            return  Blade::render($views, [
                'key' => $key,
                'block' => $block,
                'chartTypeId' => $chartTypeId,
                'chartJson' => $chartJson,
                'dataQuery' => $dataQuery,
                'series' => $series,
                'tableColumns' => $tableColumns
            ]);
        }
    }
}
