<?php

namespace App\View\Components\Renderer\Report;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class ChartBar3v3 extends Component
{

    public function __construct(
        private $dataSource = [],

    ) {
        //
    }

    public function render()
    {
        $dataWidgets = $this->dataSource;
        // dd($dataWidgets);
        #$count = $dataWidgets['meta']['count'];
        $titleTooltip = CurrentUser::isAdmin() ? $dataWidgets['key_name'] : null;
        return view(
            'components.renderer.report.chart-bar3-v3',
            [
                'chart_type' => $dataWidgets['chart_type'],
                'key' => $dataWidgets['key'],
                'meta' => $dataWidgets['meta'],
                'metric' => $dataWidgets['metric'],
                'dimensions' => $dataWidgets['dimensions'],
                'titleTooltip' => $titleTooltip,
                'lineSeries'=>$dataWidgets['line_series'] ?? [],
            ]
        );
    }
}
