<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Reports\Widgets\WidgetGroup;

use Illuminate\View\Component;

class PivotChart extends Component
{


    public function __construct(
        private $key = '',
        private $dataSource = [],
        private $showValue = false,
        private $showDataLabel = true,
        private $width ='',
        private $height ='',
    ) {
    }


    public function render()
    {
        $allWidgets = WidgetGroup::getAllGroupWidgets();
        $dataSource = $this->dataSource;
        $widget = $allWidgets[$this->key];
        // dd($widget, $this->key);
        $widget['title_a'] = $this->key;
        $widget['title_b'] = $this->key;
        // change (meta + metric) value by key
        $widget['meta'] = $dataSource['meta'];
        $widget['metric'] = $dataSource['metric'];
        // dd($widget);
        return view("components.renderer.report.pivot-chart", [
            'widget' => $widget,
            'showValue' => $this->showValue,
            'showDataLabel' => $this->showDataLabel,
            'width' => $this->width,
            'height' => $this->height,
        ]);
    }
}
