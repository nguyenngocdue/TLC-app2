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
            // return ['id' => $month, 'name' => date("F", mktime(0, 0, 0, $month, 1))];
            $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);
            return ['id' => $month, 'name' => $formattedMonth];
        }, $months);


        // dd($dataSource);
        return $dataSource;
    }
}
