<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DocumentReport;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;

abstract class Report_ParentDocumentController extends Report_ParentController2
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
        $sqlStr = [];
        $manyModeParams = $this->createManyParamsFromDates($modeParams);
        foreach ($manyModeParams as $key => $modeParam) {
            $sqlStr[$key] = $this->getSqlStr($modeParam);
        }
        return $sqlStr;
    }
}
