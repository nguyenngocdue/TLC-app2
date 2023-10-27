<?php

namespace App\Http\Controllers\Reports\Widgets;

use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\DBTable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class WidgetGroup
{
    private static function makeDataSource(&$widget)
    {
        $table = null;
        $projectId = null;
        $widget['params'] = (array) json_decode($widget['params']);
        $widget['params']['table_widget'] = $table;
        $widget['params']['check_id'] = $projectId;

        $widget[''] = $projectId;
        $fn = $widget['fn'];
        // $table_a = $widget['params']['table_a'];
        // $model_a = DBTable::fromNameToModel($table_a);
        // $meta_and_metric = $model_a->getMetaForChart($fn, $widget, $widget['params']);
        // $app = LibApps::getAll();
        $result = [
            "title_a" => $widget["section_title"],
            // "title_a" => $app[$widget["section_title"]]['title'],
            "title_b" => $widget["widget_title"],
            // "title_b" => $app[$widget["widget_title"]]['title'],
            // "meta" => $meta_and_metric['meta'],
            // "metric" => $meta_and_metric['metric'],
            // "chartType" => $meta_and_metric['chartType'],
            // "hidden" => $meta_and_metric['hidden'],
        ];
        // dump($result);
        return $result;
    }

    public static function getAllGroupWidgets()
    {
        $allWidgets = LibWidgets::getAll();
        // dd($allWidgets);
        $allWidgets = array_map(fn ($widget) => array_merge(self::makeDataSource($widget), $widget), $allWidgets);
        $allWidgets = array_filter($allWidgets, fn ($widget) => !$widget['hidden']);
        // dump($allWidgets);
        // $allWidgetGroups = Arr::groupByToChildren($allWidgets, 'title_a');
        // $allWidgets = array_merge(...array_column($allWidgetGroups, 'children'));
        return $allWidgets;
    }
}
