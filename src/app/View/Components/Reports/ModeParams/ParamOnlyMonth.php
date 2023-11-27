<?php

namespace App\View\Components\Reports\ModeParams;

use App\Utils\Support\DateReport;
use App\View\Components\Reports\ParentParamReports;

class ParamOnlyMonth extends ParentParamReports
{
    protected $referData = 'quarter_time';
    protected function getDataSource()
    {
        $isNumber = $this->getParams()['showNumber'];
        $showNow = $this->getParams()['showNow'];
        $m = $showNow ? (int)date("m") : 12;
        $months = range(1, $m);
        $dataSource = array_map(function ($month) use ($isNumber) {
            
            $formattedMonth = $isNumber ? str_pad($month, 2,"0", STR_PAD_LEFT): DateReport::getMonthAbbreviation2((int)$month);
            if($month <= 3){
                return (object)['id' => $month, 'name' => $formattedMonth, 'quarter_time' => 1];
            }
            elseif ($month<=6 && $month > 3) {
                return (object)['id' => $month, 'name' => $formattedMonth, 'quarter_time' => 2];
            }
            elseif ($month<=9 && $month > 6) {
                return (object)['id' => $month, 'name' => $formattedMonth, 'quarter_time' => 3];
            } else{
                return (object)['id' => $month, 'name' => $formattedMonth, 'quarter_time' => 4];
            }
        }, $months);
        // dump($dataSource);
        return $dataSource;
    }
}
