<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Workflow\LibStatuses;

trait TraitLegendReport
{
    function createLegendData($legendData)
    {
        $dataSource = $legendData['dataSource'];
        $title = $legendData['title'];
        $data = [];
        foreach ($dataSource as $status => $value) {
            $bg1 =  $value['color'] . '-' . $value['color_index'];
            $bg2 = $value['color'] . '-' . $value['color_index'] + 600;
            $data[$title][$status] = (object)[
                'title' =>  $value['title'],
                'legend_color' =>  'bg-' . $bg1 . ' text-' . $bg2,
                'legend_icon' => $value['icon'] ?? '',
            ];
        }
        return $data;
    }
}
