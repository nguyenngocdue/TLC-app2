<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Sub_project;
use App\View\Components\Reports\ParentTypeParamReport;
use Illuminate\Support\Facades\DB;

class ParamOnlyMonth extends ParentTypeParamReport
{
    protected function getDataSource()
    {
        $months = range(1, 12);
        $dataSource = array_map(function ($month) {
            return ['id' => $month, 'name' => date("F", mktime(0, 0, 0, $month, 1))];
        }, $months);


        // dd($dataSource);
        return $dataSource;
    }
}
