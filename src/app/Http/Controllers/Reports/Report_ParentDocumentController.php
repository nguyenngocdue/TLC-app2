<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use Illuminate\Support\Str;

abstract class Report_ParentDocumentController extends Report_ParentController
{
    
   //select date in document-prod_routing_010
    public function createManyParamsFromDates($modeParams)
    {
        $pickerDate = $modeParams['picker_date'];
        $dates = explode("-", $pickerDate);
        [$fromDate, $toDate] = [trim($dates[0]), trim($dates[1])];

        $manyDates = PivotReport::getDatesBetween($fromDate, $toDate);
        $manyDates = array_map(fn ($item) => Report::formatDateString($item), $manyDates);

        $manyModeParams = array_map(function ($item) use ($modeParams) {
            $modeParams['picker_date'] =  $item;
            return $modeParams;
        }, $manyDates);
        return $manyModeParams;
    }


    protected function getSqlStr($modeParams) {
        return [];
    }

    protected function createArraySqlFromSqlStr($modeParams)
    {
        return [];
    }
}
