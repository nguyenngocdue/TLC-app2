<?php

namespace App\View\Components\Renderer\Report;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ChartBarManyColumns extends Component
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
        private $keyNameManage = "",
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
        $titleTooltip = CurrentUser::isAdmin() ? $this->key : null;
        return view(
            'components.renderer.report.chart-bar-many-columns',
            [
                'key' => $this->key,
                'meta' => $meta,
                'metric' => $metric,
                'showValue' => $this->showValue,
                'titleChart' => $this->titleChart,
                'dimensions' => $this->dimensions,
                'titleTooltip' => $titleTooltip,
                'keyNameManage' => $this->keyNameManage,
            ]
        );
    }
}
