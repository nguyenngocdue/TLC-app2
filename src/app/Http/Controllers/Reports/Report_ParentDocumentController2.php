<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DocumentReport;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;

abstract class Report_ParentDocumentController2 extends Report_ParentController2
{

   //select date in document-prod_routing_010
    protected function createManyParamsFromDates($params)
    {
        $pickerDate = $params['picker_date'];
        $dates = explode("-", $pickerDate);
        [$fromDate, $toDate] = [trim($dates[0]), trim($dates[1])];

        $manyDates = PivotReport::getDatesBetween($fromDate, $toDate);
        $manyDates = array_map(fn ($item) => Report::formatDateString($item), $manyDates);

        $params = array_map(function ($item) use ($params) {
            $params['picker_date'] =  $item;
            return $params;
        }, $manyDates);
        return $params;
    }

    protected function getSqlStr($params) {
        return [];
    }

    protected function getCurrentDateRange() {
		$currentYearMonth = date("Y-m");
		$sixMonthsAgo = date("Y-m", strtotime("-6 months"));
		return ['from' => $sixMonthsAgo, 'to' => $currentYearMonth];
	}

	protected function parseDateRange($dateRange) {
		$parts = explode(" - ", $dateRange);
		list($fromDay, $fromMonth, $fromYear) = explode("/", $parts[0]);
		$fromDate = date("Y-m", strtotime("$fromYear-$fromMonth-$fromDay"));
		list($toDay, $toMonth, $toYear) = explode("/", $parts[1]);
		$toDate = date("Y-m", strtotime("$toYear-$toMonth-$toDay"));
		return ['from' => $fromDate, 'to' => $toDate];
	}
}
