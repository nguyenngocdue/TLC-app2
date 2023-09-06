<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Reports\Widgets\WidgetGroup;

use Illuminate\View\Component;

class PivotChart extends Component
{


    public function __construct(
        private $key = '',
        private $dataSource = [],
    ) {
    }


    public function render()
    {
        $allWidgets = WidgetGroup::getAllGroupWidgets();
        $dataSource = $this->dataSource;
        $widget = $allWidgets[$this->key];

        // change (meta + metric) value by key
        $widget['meta'] = $dataSource['meta'];
        $widget['metric'] = $dataSource['metric'];
        // dd($widget);
        return view("components.renderer.report.pivot-chart", [
            'widget' => $widget
        ]);
    }
}
