<?php

namespace App\View\Components\Dashboards;

use App\Http\Controllers\Workflow\LibApps;
use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\DBTable;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class WidgetGroups extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $table = null,
        private $projectId = null,
    ) {
        //
    }

    private function makeDataSource(&$widget)
    {
        $widget['params'] = (array) json_decode($widget['params']);
        $widget['params']['table_widget'] = $this->table;
        $widget['params']['check_id'] = $this->projectId;

        $widget[''] = $this->projectId;
        $fn = $widget['fn'];
        $table_a = $widget['params']['table_a'];
        $model_a = DBTable::fromNameToModel($table_a);
        $meta_and_metric = $model_a->getMetaForChart($fn, $widget, $widget['params']);
        // $app = LibApps::getAll();
        $result = [
            "title_a" => $widget["section_title"],
            // "title_a" => $app[$widget["section_title"]]['title'],
            "title_b" => $widget["widget_title"],
            // "title_b" => $app[$widget["widget_title"]]['title'],
            "meta" => $meta_and_metric['meta'],
            "metric" => $meta_and_metric['metric'],
            "chartType" => $meta_and_metric['chart_type'],
            "hidden" => $meta_and_metric['hidden'],
        ];
        // dump($result);
        return $result;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $allWidgets = LibWidgets::getAll();
        // dump($allWidgets);
        $allWidgets = array_map(fn ($widget) => array_merge($this->makeDataSource($widget), $widget), $allWidgets);
        $allWidgets = array_filter($allWidgets, fn ($widget) => !$widget['hidden']);
        // dump($allWidgets);
        $allWidgetGroups = Arr::groupByToChildren($allWidgets, 'title_a');
        // dd($allWidgetGroups);

        return view('components.dashboards.widget-groups', [
            'allWidgetGroups' => $allWidgetGroups,
            'table' => $this->table,
            'projectId' => $this->projectId,
        ]);
    }
}
