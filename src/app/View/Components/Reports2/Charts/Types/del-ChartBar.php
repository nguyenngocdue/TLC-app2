<?php

namespace App\View\Components\Reports2\Charts\Types;

use App\Http\Controllers\Reports\TraitCreateSQL;
use App\View\Components\Reports2\Charts\TraitTransformationData;
use App\View\Components\Reports2\TraitDataColumnReport;
use Illuminate\View\Component;

class ChartBar extends Component
{
    use TraitDataColumnReport;
    use TraitTransformationData;
    public function __construct(
        private $chartType,
        private $dataQuery,
        private $chartJson,
        private $tableColumns,
    ) {
    }


    public function render()
    {
        $dataQuery = $this->dataQuery;
        $tableColumns = $this->tableColumns;
        $typeOfTrans = "rows_to_fields";

        switch ($typeOfTrans) {
            case 'rows__to_fields':
                $transformation = $this->makeRowsToFields($dataQuery, $tableColumns);
                break;
            default:
                $transformation = $this->mapDataQueryToColumns($dataQuery, $tableColumns);
                break;
        }
        $chartType = $this->chartType;
        $series = $this->makeSeriesChart($transformation, $tableColumns);

        $chartJson = json_decode($this->chartJson);
        $chartJson = array_pop($chartJson);
        return view('components.reports2.charts.types.chart-bar', [
            'series' => $series,
            'chartType' => $chartType,
            'chartJson' => $chartJson,
        ]);
    }
}
