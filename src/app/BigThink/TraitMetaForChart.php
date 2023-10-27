<?php

namespace App\BigThink;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait TraitMetaForChart
{
    function makeOthers($dataSource)
    {
        $values = array_map(fn ($item) => $item->metric_count, $dataSource);
        // dump($values);
        $median = Arr::median($values);
        $result = [];
        $other = (object)(['metric_id' => 99999, 'metric_name' => "Others", 'metric_count' => 0,]);
        if (count($dataSource) > 10) {
            foreach ($dataSource as $item) {
                if ($item->metric_count > $median) {
                    $result[] = $item;
                } else {
                    $other->metric_count += $item->metric_count;
                    $descriptions[] = $item->metric_name;
                }
            }
        } else {
            $result = $dataSource;
        }
        if ($other->metric_count > 0) {
            $other->metric_name = "Others";
            // $other->metric_name = "Others: " . join(", ", $descriptions);
            $result[] = $other;
        }

        return $result;
    }

    function makeMeta($metric)
    {
        return [
            'labels' => '[' . join(", ", array_map(fn ($item) =>  "'" . $item->metric_name . "'", $metric)) . ']',
            'numbers' => '[' . join(", ", array_map(fn ($item) =>  $item->metric_count, $metric)) . ']',
            'max' => array_sum(array_map(fn ($item) =>  $item->metric_count, $metric)),
            'count' => count($metric),
        ];
    }

    // function getMetaForChart($sqlClassName, $widget, $params)
    // {
    //     $className = "App\\Http\\Controllers\\Reports\\Widgets\\$sqlClassName";
    //     // $sql = (new $className)($params);
    //     // $result = DB::select($sql);
    //     // $result = $this->makeOthers($result);

    //     // $meta = $this->makeMeta($result);
    //     return [
    //         'meta' => [],
    //         'metric' => [],
    //         'chartType' => $widget['chart_type'],
    //         'hidden' => $widget['hidden'],
    //     ];
    // }
    function getMetaForChart($sqlClassName, $widget, $params)
    {
        $className = "App\\Http\\Controllers\\Reports\\Widgets\\$sqlClassName";
        $sql = (new $className)($params);
        $result = DB::select($sql);
        $result = $this->makeOthers($result);

        $meta = $this->makeMeta($result);
        return [
            'meta' => $meta,
            'metric' => $result,
            'chart_type' => $widget['chart_type'],
            'hidden' => $widget['hidden'],
        ];
    }
}
