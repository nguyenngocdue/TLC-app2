<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Sub_project;
use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamOnlyMonth extends ParentParamReports
{
    protected $referData = 'quarter_time';
    protected function getDataSource()
    {
        $months = range(1, 12);
        $dataSource = array_map(function ($month) {
            $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);
            if($month <= 3){
                return ['id' => $month, 'name' => $formattedMonth, 'quarter_time' => 1];
            }
            elseif ($month<=6 && $month > 3) {
                return ['id' => $month, 'name' => $formattedMonth, 'quarter_time' => 2];
            }
            elseif ($month<=9 && $month > 6) {
                return ['id' => $month, 'name' => $formattedMonth, 'quarter_time' => 3];
            } else{
                return ['id' => $month, 'name' => $formattedMonth, 'quarter_time' => 4];
            }
        }, $months);
        // dump($dataSource);
        return $dataSource;
    }
}
