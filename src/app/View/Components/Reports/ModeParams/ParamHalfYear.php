<?php

namespace App\View\Components\Reports\ModeParams;

use App\Utils\Support\DateReport;
use App\View\Components\Reports\ParentParamReports;
use App\View\Components\Reports\ParentTypeParamReport;
use DateTime;

class ParamHalfYear extends ParentParamReports
{
    protected $referData = 'year';
    protected function getDataSource()
    {  
        $years = [2021,2022,2023];
        $dateOfHalfYear = DateReport::getHalfYearPeriods(2023);
        $result = [];
        foreach ($years as $year){
            foreach ($dateOfHalfYear as $key => $value){
                [$start_date, $end_date] = explode('/',$value);
                $s = DateReport::basicFormatDateString($start_date, 'd/m');
                $e = DateReport::basicFormatDateString($end_date, 'd/m');
                $result[] = (object)[
                    'id' => $key,
                    'name' => $key === 'start_half_year' 
                                ? 'Start'.' (' . $s .'-'.$e.')' 
                                : 'End' .' (' . $s .'-'.$e.')',
                    $this->referData => $year,
                ];
            }
        }
        return $result;
    }
 
}
