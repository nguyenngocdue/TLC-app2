<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DateReport;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use Exception;
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

	protected function getCurrentDateRange()
	{
		$currentYearMonth = date("Y-m");
		$sixMonthsAgo = date("Y-m", strtotime("-6 months"));
		return ['from' => $sixMonthsAgo, 'to' => $currentYearMonth];
	}

	protected function parseDateRange($dateRange)
	{
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


	private function calculateEachYears($data)
	{
		foreach ($data as $k2 => &$tco2es) {
			$tco2eYears = $tco2es['tco2e'];
			$years = array_keys($tco2eYears);
			$differences = [];
			$count = count($tco2eYears) - 1;
			$differences[reset($years)] = null;
			for ($i = $count; $i > 0; $i--) {
				$currentYear = $years[$i];
				$previousYear = $years[$i - 1];
				$difference = $tco2eYears[$previousYear] && !is_null($tco2eYears[$currentYear]) ?
					round((($tco2eYears[$currentYear] - $tco2eYears[$previousYear]) / $tco2eYears[$previousYear]) * 100, 2) : null;
				$differences[$currentYear] = $difference;
			}
			asort($differences);
			$tco2es['differences'] = $differences;
		}
		// dd($data);
		return $data;
	}

	protected function calculateYearlyDifference($data)
	{
		foreach ($data as $k1 => &$items) {
			foreach ($items as $k2 => &$values) {
				$values = $this->calculateEachYears($values);
			}
		}
		return $data;
	}


	protected  function makeDataByTypeTime($fieldsTime, $dataSource, $typeTime)
	{
		// dd($dataSource, $typeTime);
		$dataTimes = [];
		$totalEmission = [];
		foreach ($dataSource as $k1 => $items) {
			$emissions = [];
			foreach ($items as $values) {
				$values = (array)$values;
				foreach ($fieldsTime as $time) {
					try {
						$dataTimes[$values['ghg_tmpl_id']][$typeTime][$time]['tco2e'][$k1] = $values[$time] ?  round($values[$time], 2) : null;
						$emissions[$time][$k1][] = $values[$time] ? $values[$time] : null;
					} catch (Exception $e) {
						continue;
					}
				}
			}
			foreach ($emissions as $field => $items) {
				foreach ($items as $year => $values) {
					$totalEmission[$field]['tco2e'][$year] = array_sum($values) ? array_sum($values) : null;
				}
			}
		}
		// dd($dataTimes);
		// dd($dataTimes);
		$yearDifferences = $this->calculateYearlyDifference($dataTimes);
		$totalEmission = $this->calculateEachYears($totalEmission);
		$dataSource = array_map(function ($items) use ($yearDifferences, $typeTime) {
			$items = (array)$items;
			foreach (array_keys($yearDifferences) as $id) {
				if ($id === $items['ghg_tmpl_id']) {
					$items[$typeTime] = $yearDifferences[$id][$typeTime];
					break;
				}
				if (!$items['ghg_tmpl_id']) {
					$items[$typeTime] = $yearDifferences[""][$typeTime];
					break;
				}
			}
			return $items;
		}, $dataSource->first());

		$groupByScope = Report::groupArrayByKey($dataSource, 'scope_id');
		$groupByScope = ['scopes' => array_map(fn ($item) => Report::groupArrayByKey($item, 'ghgcate_id'), $groupByScope)];
		$groupByScope['total_emission'] = $totalEmission;
		// dd($groupByScope);
		return $groupByScope;
	}
}
