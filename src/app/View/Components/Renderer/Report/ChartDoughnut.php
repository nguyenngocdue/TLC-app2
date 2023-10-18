<?php

namespace App\View\Components\Renderer\Report;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ChartDoughnut extends Component
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
        $meta =  $this->meta;
        $count = $meta['count'];
        if ($count == 0) return "<x-renderer.emptiness />";
        return view(
            'components.renderer.report.chart-doughnut',
            [
                'key' => $this->key,
                'meta' => $meta,
                'metric' => $this->metric,
                'showValue' => $this->showValue,
                'dimensions' => $this->dimensions,
            ]
        );
    }
}
