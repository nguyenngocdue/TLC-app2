<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Sub_project;
use App\View\Components\Reports\ParentTypeParamReport;
use Illuminate\Support\Facades\DB;

class ParamQuarterTime extends ParentTypeParamReport
{
    protected function getDataSource()
    {
        $numbers = range(1, 4);
        $dataSource = array_map(function ($number) {
            return ['id' => $number, 'name' => 'QTR'.$number];
        }, $numbers);
        return $dataSource;
    }
}
