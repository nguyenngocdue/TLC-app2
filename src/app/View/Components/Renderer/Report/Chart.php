<?php

namespace App\View\Components\Renderer\Report;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Chart extends Component
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
        private $showDataLabel = true,
        private $width = '',
        private $height = '',
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
        $widgetParams = $this->widgetParams;
        // dump($this->widgetParams);

        $count = $meta['count'];
        if ($count == 0) return "<x-renderer.emptiness />";

        $result = [];
        foreach ($metric as $value) {
            $type = $widgetParams['table_a'];
            $filed = $widgetParams['key_a'] ?? null;
            $routeDefault = route($type . '.index');
            // $href = $routeDefault . '?_entity=' . $type . '&' . $filed . '%5B%5D=' . $value->metric_id . '&action=updateAdvanceFilter';
            // $result[] = $href;
        }
        // $meta['href'] = '[' . join(", ", array_map(fn ($item) =>  "'" . $item . "'", $result)) . ']';

        // dd($this->key,$metric, $meta);
        return view(
            'components.renderer.report.chart',
            [
                'key' => $this->key,
                'meta' => $meta,
                'metric' => $metric,
                'showValue' => $this->showValue,
                'showDataLabel' => $this->showDataLabel,
                'width' => $this->width,
                'height' => $this->height,

            ]
        );
    }
}
