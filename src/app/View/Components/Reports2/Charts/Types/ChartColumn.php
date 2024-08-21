<?php

namespace App\View\Components\Reports2\Charts\Types;

use App\Http\Controllers\Reports\TraitCreateSQL;
use App\View\Components\Reports2\Charts\TraitTransformationData;
use App\View\Components\Reports2\TraitReportDataAndColumn;
use Illuminate\View\Component;

class ChartColumn extends Component
{
    use TraitReportDataAndColumn;
    use TraitTransformationData;
    use TraitTransformationData;

    protected $chartTypeName = 'column';

    public function __construct(
        private $chartTypeId,
        private $queriedData,
        private $chartJson,
        private $tableColumns = [],
        private $key
    ) {}

    public function render()
    {
        $queriedData = $this->queriedData;
        $tableColumns = $this->tableColumns;
        $typeOfTrans = "rows_to_fields";

        switch ($typeOfTrans) {
            case 'rows__to_fields':
                $transformation = $this->makeRowsToFields($queriedData, $tableColumns);
                break;
            default:
                $transformation = $this->groupNames($queriedData, $tableColumns);
                break;
        }
        $chartTypeId = $this->chartTypeId;
        $series = $this->makeSeriesChart($transformation, $tableColumns);

        $chartJson = json_decode($this->chartJson);
        $chartJson = array_pop($chartJson);


        return view('components.reports2.charts.types.chart-column', [
            'series' => $series,
            'chartTypeId' => $chartTypeId,
            'chartJson' => $chartJson,
            'key' => $this->key,

        ]);
    }
}
