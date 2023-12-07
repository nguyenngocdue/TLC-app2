<?php

namespace App\View\Components\Renderer\Report;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ChartBar2 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $meta = [],
        private $metric = [],
        private $key = "",
        private $widgetParams = [],
        private $showValue  = false,
        private $titleChart = '',
        private $dimensions = [],
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $metric = $this->metric;
        $meta = $this->meta;

        $count = $meta['count'];
        if ($count == 0) return "<x-renderer.emptiness />";
        $tooltipComponent = CurrentUser::isAdmin() ? "(Component name: Chart-bar2" : "";
        return view(
            'components.renderer.report.chart-bar2',
            [
                'tooltipComponent' => $tooltipComponent,
                'key' => $this->key,
                'meta' => $meta,
                'metric' => $metric,
                'showValue' => $this->showValue,
                'titleChart' => $this->titleChart,
                'dimensions' => $this->dimensions,
            ]
        );
    }
}
