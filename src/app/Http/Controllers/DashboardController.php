<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\DBTable;

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

    private function a($widget)
    {
        $fn = $widget['fn'];
        $table_a = $widget['table_a'];
        $table_b = $widget['table_b'];
        // $key_a = $widget['key_a'];
        // $key_b = $widget['key_b'];
        // $global_filter = $widget['global_filter'];
        $model_a = DBTable::fromNameToModel($table_a);
        $model_b = DBTable::fromNameToModel($table_b);
        $meta_and_metric = $model_a->getMetaForChart($fn, $widget);
        return [
            "title_a" => $model_a->getMenuTitle(),
            "title_b" => $model_b->getMenuTitle(),
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
        return view(
            'dashboards.dashboard',
            [
                'allWidgets' => $allWidgets,
            ]
        );
    }
}
