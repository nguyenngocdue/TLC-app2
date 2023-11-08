<?php

namespace App\View\Components\Reports\ModeParams;

use App\Utils\Support\DateReport;
use App\View\Components\Reports\ParentParamReports;
use App\View\Components\Reports\ParentTypeParamReport;
use DateTime;

class ParamWeeksOfYear extends ParentParamReports
{
    protected $referData = 'year';
    protected function getDataSource()
    {        
        $years = [2021,2022,2023];
        $weeks = [];
        foreach ($years as $year) {
            $weeksData = DateReport::getWeeksInYear($year);
            foreach ($weeksData as $keyWeek => $dates){
                $dayAndMonths = [];
                foreach ($dates as $key => $date){
                    $dateTime = new DateTime($date);
                    $formattedDate = $dateTime->format('d/m');
                    $dayAndMonths[$key] = $formattedDate;
                }
                $keyWeek = str_pad($keyWeek, 2, '0', STR_PAD_LEFT);
                $weeks[] = (object)[
                    // 'id' =>'W'.$keyWeek.'-'.substr($year, -2),
                    'id' =>(int)$keyWeek,
                    'name'=> 'W'.$keyWeek.'/'.$year.' '.'('.$dayAndMonths['start_date']. '-'.$dayAndMonths['end_date'].')',
                    $this->referData => $year,
                ];
            }
        }
        // dump($weeks);
        return $weeks;
    }
}
