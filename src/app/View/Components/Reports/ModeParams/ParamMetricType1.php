<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Ghg_metric_type_1;
use App\Utils\Support\StringReport;
use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamMetricType1 extends ParentParamReports
{
    protected $referData = 'ghg_tmpl';
    protected function getDataSource()
    {
        $list = Ghg_metric_type_1::where('deleted_at', NULL)->get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $item) {
            $dataSource[] = ['id' => $item['id'], 
                            'name' => $item['name'], 
                            'ghg_tmpl'=> $item['ghg_metric_type_id']];
        } 
        // dd($dataSource);

        return $dataSource;
    }
}
