<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DateReport;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use Illuminate\Support\Str;

abstract class Report_ParentDocument2Controller extends Report_Parent2Controller
{
   //select date in document-prod_routing_010
    protected function createManyParamsFromDates($params)
    {
        $pickerDate = $params['picker_date'];
        $dates = explode("-", $pickerDate);
        [$fromDate, $toDate] = [trim($dates[0]), trim($dates[1])];
        
        $manyDates = PivotReport::getDatesBetween($fromDate, $toDate);
        $manyDates = array_map(fn ($item) => DateReport::formatDateString($item), $manyDates);

        $params = array_map(function ($item) use ($params) {
            $params['picker_date'] =  $item;
            return $params;
        }, $manyDates);
        return $params;
    }

    public function getSqlStr($params)
	{
		$sql =  "";
		return $sql;
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

    protected function createArraySqlFromSqlStr($params)
    {
        $sqlStr = [];
        return $sqlStr;
    }

    protected  function makeDataByTypeTime($fieldsTime, $dataSource, $typeTime) {
        $dataTimes = [];
		$totalEmission = [];
		foreach ($dataSource as $k1 => $items) {
			foreach ($items as $values) {
				$values = (array)$values;
				foreach ($fieldsTime as $time) {
					$dataTimes[$values['ghg_tmpl_id']][$typeTime][$time][$k1] = $values[$time];
					$totalEmission[$k1][$time][] = $values[$time];
				}
			}
            dd($totalEmission);
			foreach($totalEmission as $year => $items) {
				foreach($items as $time => $values) {
					if(is_array($values)){
						$totalEmission[$year][$time] = array_sum($values);
					}
				}
			}
		}
        // dump($totalEmission);
		$dataSource = array_map(function ($items) use ($dataTimes, $typeTime) {
			$items = (array)$items;
			foreach (array_keys($dataTimes) as $id) {
				if ($id === $items['ghg_tmpl_id']) {
					$items[$typeTime] = $dataTimes[$id][$typeTime];
					break;
				}
				if (!$items['ghg_tmpl_id']) {
					$items[$typeTime] = $dataTimes[""][$typeTime];
					break;
				}
			}
			return $items;
		}, $dataSource->first());

		$groupByScope = Report::groupArrayByKey($dataSource, 'scope_id');
		$groupByScope = ['scopes' => array_map(fn ($item) => Report::groupArrayByKey($item, 'ghgcate_id'), $groupByScope)];
		$groupByScope['total_emission'] = $totalEmission;
        return collect($groupByScope);
    }
}
