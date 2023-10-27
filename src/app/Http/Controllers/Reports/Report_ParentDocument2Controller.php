<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DateReport;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;
use Exception;
use Illuminate\Support\Str;

abstract class Report_ParentDocument2Controller extends Report_Parent2Controller
{
	use TraitForwardModeReport;
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


	private function calculateEachYears($data, $typeTime)
	{
		foreach ($data as $k2 => &$tco2es) {
			$tco2eYears = $tco2es['tco2e'];
			$years = array_keys($tco2eYears);
			$differences = [];
			$count = count($tco2eYears) - 1;
			if ($typeTime !== 'years') {
				if (count($years) > 1) {
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
				} else {
					$y = key($tco2es['tco2e']);// get one year
					$years = array_keys($data);
					$differences[reset($years)] = null;

					$count = count($years) - 1;
					for ($i = $count; $i > 0; $i--) {
						$valCurrentYear = $years[$i];
						$valPreviousYear = $years[$i - 1];
						$tco2eYears1 = $data[$valPreviousYear]['tco2e'][$y];
						$tco2eYears2 = $data[$valCurrentYear]['tco2e'][$y];
						// dd($y);

						$difference = $tco2eYears1  ? round(($tco2eYears2 - $tco2eYears1) * 100 / $tco2eYears1, 2) : null;
						$differences[$valCurrentYear] = $difference;
					}
					$tco2es['differences'] = $differences;
					// dd($years);
				}
			} else {
				$years = array_keys($data);
				$differences[reset($years)] = null;
				// dd($differences);
				$count = count($years) - 1;
				for ($i = $count; $i > 0; $i--) {
					$valCurrentYear = $years[$i];
					$valPreviousYear = $years[$i - 1];
					$tco2eYears1 = $data[$valPreviousYear]['tco2e'][$valPreviousYear];
					$tco2eYears2 = $data[$valCurrentYear]['tco2e'][$valCurrentYear];

					$difference = $tco2eYears1  ? round(($tco2eYears2 - $tco2eYears1) * 100 / $tco2eYears1, 2) : null;
					$differences[$valCurrentYear] = $difference;
				}
				$tco2es['differences'] = $differences;
			}
		}
		// dump($data);
		return $data;
	}

	protected function calculateYearlyDifference($data, $typeTime)
	{
		foreach ($data as $k1 => &$items) {
			foreach ($items as $k2 => &$values) {
				$values = $this->calculateEachYears($values, $typeTime);
			}
		}
		return $data;
	}


	protected  function makeDataByTypeTime($fieldsTime, $dataSource, $typeTime)
	{
		// dd($dataSource);
		$years = array_keys($dataSource->toArray());
		$dataTimes = [];
		$totalEmission = [];
		foreach ($dataSource as $k1 => $items) {
			$emissions = [];
			// dd($items);
			foreach ($items as $values) {
						if(count($years) > 0) {
							$values = (array)$values;
							foreach ($fieldsTime as $time) {
								try {
									$dataTimes[$values['ghg_tmpl_id']][$typeTime][$time]['tco2e'][$k1] = $values[$time] ?  round($values[$time], 2) : null;
									$emissions[$time][$k1][] = $values[$time] ? $values[$time] : null;
								} catch (Exception $e) {
									continue;
								}
							}
						} else{
							foreach ($fieldsTime as $time) {
								try {
									$quarterKeys = array_flip(["quarter1", "quarter2", "quarter3", "quarter4"]);
									$quarters = array_intersect_key($values, $quarterKeys);

									$dataTimes[$values['ghg_tmpl_id']][$typeTime][$time]['tco2e'][$k1] = $values[$time] ?  round($values[$time], 2) : null;
									$emissions[$time][$k1][] = $values[$time] ? $values[$time] : null;
								} catch (Exception $e) {
									continue;
								}
							}

							// dd($fieldsTime);
						}
					}
					foreach ($emissions as $field => $items) {
						foreach ($items as $year => $values) {
							$totalEmission[$field]['tco2e'][$year] = array_sum($values) ? array_sum($values) : null;
						}
					}

		}
		// dd($dataTimes);
		$yearDifferences = $this->calculateYearlyDifference($dataTimes, $typeTime);
		// dump($yearDifferences);
		$totalEmission = $this->calculateEachYears($totalEmission, $typeTime);
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

	protected function makeDataChart($dataSource, $chartType, $dimensions, $key) {
		$labels = StringReport::arrayToJsonWithSingleQuotes(array_keys($dataSource));
		$numbers = StringReport::arrayToJsonWithSingleQuotes(array_values($dataSource));
		$max = count(array_values($dataSource));
		$count = count($dataSource);
		$meta = [
			'labels' => $labels,
			'numbers' => $numbers,
			'max' => $max,
			'count' => $count
		];
		// information for metric data
		$metric = [];
		array_walk($dataSource, function ($value, $key) use (&$metric) {
			return $metric[] = (object) [
				'meter_id' => $key,
				'metric_name' => $value
			];
		});

		// related to dimensions AxisX and AxisY
		$dimensions['height'] = $max/2*30;

		// Set data for widget
		$widgetData =  [
			"title_a" => "title_a".$key,
			"title_b" => "title_b",
			'meta' => $meta,
			'metric' => $metric,
			'chart_type' => $chartType,
			'title_chart' => '',
			'dimensions' => $dimensions,
		];
		$data['widget_'. $key] = $widgetData;
		return $data;
	}
}
