<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Ghg_metric_type_2;
use App\View\Components\Reports\ParentParamReports;

class ParamMetricType2 extends ParentParamReports
{
    protected $referData = 'metric_type1';
    protected function getDataSource()
    {
        $list = Ghg_metric_type_2::where('deleted_at', NULL)->get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $item) {
            $dataSource[] = ['id' => $item['id'], 
                            'name' => $item['name'], 
                            'metric_type1'=> $item['ghg_metric_type_1_id']];
        } 
        return $dataSource;
    }
}
