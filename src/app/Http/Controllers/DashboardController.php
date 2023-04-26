<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibApps;
use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\DBTable;
use Illuminate\Support\Arr;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function getType()
    {
        return "dashboard";
    }

    private function a(&$widget)
    {
        $widget['params'] = (array) json_decode($widget['params']);
        $fn = $widget['fn'];
        $table_a = $widget['params']['table_a'];
        $model_a = DBTable::fromNameToModel($table_a);
        $meta_and_metric = $model_a->getMetaForChart($fn, $widget, $widget['params']);
        $app = LibApps::getAll();
        return [
            "title_a" => $app[$widget["section_title"]]['title'],
            "title_b" => $app[$widget["widget_title"]]['title'],
            "meta" => $meta_and_metric['meta'],
            "metric" => $meta_and_metric['metric'],
            "chartType" => $meta_and_metric['chartType'],
            "hidden" => $meta_and_metric['hidden'],
        ];
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $allWidgets = LibWidgets::getAll();
        $allWidgets = array_map(fn ($widget) => array_merge($this->a($widget), $widget), $allWidgets);
        $allWidgets = array_filter($allWidgets, fn ($widget) => !$widget['hidden']);
        // dump($allWidgets);
        $allWidgetGroups = Arr::groupByToChildren($allWidgets, 'title_a');
        // dump($allWidgetGroups);
        return view(
            'dashboards.dashboard',
            [
                'allWidgetGroups' => $allWidgetGroups,
            ]
        );
    }
}
