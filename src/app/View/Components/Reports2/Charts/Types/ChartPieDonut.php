<?php

namespace App\View\Components\Reports2\Charts\Types;

use App\View\Components\Reports2\Charts\TraitTransformationData;
use App\View\Components\Reports2\TraitDataColumnReport;
use Illuminate\View\Component;

class ChartPieDonut extends Component
{
    use TraitTransformationData;

    protected $chartTypeName = 'pie-donut';
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
        $series =  $this->makeSeriesChart($queriedData, $this->tableColumns);
        return view('components.reports2.charts.types.chart-pie-donut', [
            'series' => $series,
            'chartType' => $this->chartTypeId,
            'chartJson' => $this->chartJsonDeCode(),
            'key' => $this->key,
        ]);
    }
}
