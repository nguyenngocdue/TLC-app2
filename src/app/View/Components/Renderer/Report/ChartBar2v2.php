<?php

namespace App\View\Components\Renderer\Report;

use App\Utils\Support\Report;
use App\Utils\Support\StringReport;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ChartBar2v2 extends Component
{
    use TraitLabelChart;
    public function __construct(
        private $dataSource = [],
    ) {
        //
    }
    public function render()
    {
        $dataWidgets = $this->dataSource;
        $count = $dataWidgets['meta']['count'];
        if ($count == 0) return "<x-renderer.emptiness />";
        return view(
            'components.renderer.report.chart-bar2-v2',
            [
                'chart_type' => $dataWidgets['chart_type'],
                'key' => $dataWidgets['key'],
                'meta' => $dataWidgets['meta'],
                'metric' => $dataWidgets['metric'],
                'dimensions' => $dataWidgets['dimensions'],
            ]
        );
    }
}
