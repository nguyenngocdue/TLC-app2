<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitGenerateValuesFromParamsReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Models\Ghg_cat;
use App\Models\Term;
use App\Utils\Support\ArrayReport;
use App\Utils\Support\DateReport;
use App\Utils\Support\NumberReport;
use App\Utils\Support\Report;

class Ghg_sheet_070 extends Report_ParentDocument2Controller
{

	use TraitForwardModeReport;
	use TraitParamsSettingReport;
	use TraitGenerateValuesFromParamsReport;

	protected $viewName = 'document-ghg-sheet-070';
	protected $year = '2023';
	protected $mode = '070';


	public function getDataSource($params)
	{
		$years =  is_array($params['year']) ? $params['year'] : [$params['year']];
		$valueOfYears = [];
		foreach ($years as $year) {
			$params['year'] = $year;
			$instance = new Ghg_sheet_050($params);
			$dataSource = $instance->getDataSource($params)->toArray();
			$dataSource = $instance->changeDataSource($dataSource,$params);
			$valueOfYears[$year] = $dataSource;
		}
		return $valueOfYears;
	}
	public function getParamColumns($dataSource,$modeType)
	{
		return [
			[
				'title' => 'Year',
				'dataIndex' => 'year',
				'multiple' => true,
			],
			[
				'title' => 'Half Year',
				'dataIndex' => 'half_year',
				'hasListenTo' => true,
				'allowClear' => true,
			],
			[
				'title' => 'Quarter',
				'dataIndex' => 'quarter_time',
				'allowClear' => true,
				'multiple' => true,

			],
			[
				'title' => 'Month',
				'dataIndex' => 'only_month',
				'allowClear' => true,
				'multiple' => true,
				// 'hasListenTo' => true,
			],
		];
	}
	private function getValidValueByIndex($data, $index){
		// dump($data);
		foreach($data as $key => $items){
			if(Report::checkValueOfField($items, $index)) {
				$a = $items[$index];
				if(!$a) continue;
				$arr = array_map(function($item) {
					$item['months'] = array_fill_keys(array_keys($item['months']), 0); // reset value of months
					$item['total_months'] = 0;// reset value of total_months
					return $item;
				} , $a);
				return $arr;
			}
		}
		return [];
	}

	private function calculatePercentForQuarter($dataBefore, $dataAfter) {
		$result = [];
		foreach($dataBefore as $key => $value){
			$result[$key] = $dataBefore[$key] ? ($dataAfter[$key] - $dataBefore[$key])/ $dataBefore[$key] : $dataAfter[$key];
		}
		return $result;
	}

	private function calculatePercentForYears($beforeMonthSum, $afterMonthSum) {
		$number = NumberReport::calculatePercentNumber($beforeMonthSum, $afterMonthSum);
		$number = NumberReport::formatNumber($number);
		return $number;
	}
	
	
	private function calculateQuarterData($data){
		$months = array_keys($data);
		$quarters = DateReport::monthsToQuarters($months);

		$result = [];
		foreach($quarters as $k => $values){
			$valuesOfInterest = array_intersect_key($data, array_flip($values));
			$total = array_sum($valuesOfInterest);
			$result[$k] = $total;
		}
		return $result;
	}

	private function filterDataInNextYear($dataToCheck, $dataToFilter){
		// dd($dataToFilter, $dataToCheck);
		foreach($dataToFilter as $item){
			$check = false;
			foreach ($dataToCheck as $keyId => $value){
				if(isset($item[$keyId]) && $item[$keyId] === $value) {
					$check = true;
				} else {
					$check = false;
				}
			}
			if($check) return $item;
		}
		return [];
	}

	private function changeToEmptyData($data){
		$data['months'] = array_fill_keys(array_keys($data['months']), 0);
		$data['total_months'] = 0;
		return $data;
	}

	private function calculatePercentDifference($data) {
        $result = [];
        $previous = null;
        foreach ($data as $key => $current) {
            $previousValue = $previous ?? 0;
			$number = $current && $previousValue > 0 ? round(($current - $previousValue)*100/$previousValue, 2) : 0;
            $percent = $number > 0  ? 100 - $number : -1*($number !== 0 ? 100 - abs($number) : null);
            $result[$key] = NumberReport::formatNumber(number_format($percent,2));
            $previous = $current;
        }
        return $result;
    }

	private function comparisonData($data) {
		// dd($data);
		$years = array_keys($data);
		$year = last($years);
		$countLines = count($data[$year]);

		// dd(count($years) - 1, $years);
		for ($i = count($years) - 1; $i > -1; $i--) { 
			for ($index=0; $index < $countLines; $index++) {
				// data 1
				$afterYear = $years[$i]; 
				$dataMetricAfter = $data[$afterYear][$index];

				$beforeYear = $years[$i-1] ?? $afterYear - 1;
				$dbBeforeMetric = $data[$beforeYear][$index] ?? [];

				// set comparison's values for metric2
				// dump($afterYear,$dataMetricAfter);
				foreach ($dataMetricAfter as $keyOfChild => $dbAfterMonth) {
					if(!is_array($dbAfterMonth) || empty($dbAfterMonth)) continue;

					// get Id to check
					$dataToCheck = [
						"ghg_tmpls_id" => $dbAfterMonth['ghg_tmpls_id'],
						"ghg_metric_type_id" => $dbAfterMonth['ghg_metric_type_id'],
						"ghg_metric_type_1_id" => $dbAfterMonth['ghg_metric_type_1_id'],
						"ghg_metric_type_2_id" => $dbAfterMonth['ghg_metric_type_2_id'],
					];
					$_dbIndexBeforeMetric = $this->filterDataInNextYear($dataToCheck, $dbBeforeMetric);
					$dbIndexBeforeMetric = $_dbIndexBeforeMetric ? $_dbIndexBeforeMetric : $dbAfterMonth;
						if(!empty($dbIndexBeforeMetric)){

							$dbBeforeMonth = empty($_dbIndexBeforeMetric) ?  $this->changeToEmptyData($dbIndexBeforeMetric) : $_dbIndexBeforeMetric; // set value is 0 data when dbIndexBeforeMetric is empty

							$monthsAfter = $dbAfterMonth['months'];
							$monthsBefore = $dbBeforeMonth['months'];
							$afterMonthSum = array_sum($monthsAfter);
							$beforeMonthSum = array_sum($monthsBefore);
							
							$dbAfterQuarter = $this->calculateQuarterData($monthsAfter);
							$dbBeforeQuarter = $this->calculateQuarterData($monthsBefore);
							$countYear = count($years);

							
							// $show value that is calculated for percent on each item
							$percentValForQuarters = ArrayReport::calculatePercentBetween2Months($dbBeforeQuarter, $dbAfterQuarter);
							$percentValForMonths = ArrayReport::calculatePercentBetween2Months($monthsBefore, $monthsAfter);
							$percentValForYears = self::calculatePercentForYears($beforeMonthSum, $afterMonthSum);

							if($countYear === 1) {
								$percentQuarterWhenOneYear = self::calculatePercentDifference($dbAfterQuarter);
								$percentMonthWhenOneYear = self::calculatePercentDifference($monthsAfter);
							} 

							$array = [
								'meta_percent' => [
									'months' => $countYear === 1 ? $percentMonthWhenOneYear : $percentValForMonths,
									'years' => $percentValForYears,
									'quarters' => $countYear === 1 ? $percentQuarterWhenOneYear : $percentValForQuarters
									],
								];
							// show original value
							$dataRender = [
								$afterYear => [
									'months' => $dbAfterMonth['months'],
									'years' => $dbAfterMonth['total_months'],
									'quarters' => $dbAfterQuarter,
									]
								];
							$data[$afterYear][$index][$keyOfChild]['comparison_with'/* .$beforeYear */] = $array;
							$data[$afterYear][$index][$keyOfChild]['data_render'] = $dataRender;
						} else {
							// dd($_dbIndexBeforeMetric);
							$data[$afterYear][$index][$keyOfChild]['data_render'] = [];
							$data[$afterYear][$index][$keyOfChild]['comparison_with'/* .$beforeYear */] = [];
						}
					}
				}
			}
		// dd($data);
		return $data;
	}

	private function makeDataToBuildTable($dataSource) {
		$result = [];
		foreach ($dataSource as $scopeId => $values) {
			$scopeName = Term::find($scopeId)->toArray()['name'];
			foreach ($values as $ghgCatId => $childrenMetrics){
				$ghgCatName = Ghg_cat::find($ghgCatId)->toArray()['name'];
				$childMetrics = last(array_values($childrenMetrics));
				$arr1 = [];
				foreach($childMetrics as $k => $metrics){
					$firstMetrics = reset($metrics);
					$arr = [];
					if($firstMetrics){
						$ghgTmplId = isset($firstMetrics['ghg_tmpls_id']) ? $firstMetrics['ghg_tmpls_id'] : null;
						$ghgTmplName = isset($firstMetrics['ghg_tmpls_name']) ? $firstMetrics['ghg_tmpls_name'] : null;
						$arr = [
							"scope_id" => $scopeId,
							"scope_name" => $scopeName,
							"ghgcate_id" => $ghgCatId,
							'ghgcate_name' => $ghgCatName,
							'ghg_tmpl_id' => $ghgTmplId,
							'ghg_tmpl_name' => $ghgTmplName,
							'children_metrics' => $metrics
						];
					}
					$arr1[] = $arr;
				}
				$result[$scopeId][$ghgCatId] = $arr1;
			}
		}
		return $result;
	}

	private function createDateTime($params){
		// dd($params);
		$months = [];
		$result['years'] = is_array($params['year']) ? $params['year'] : [$params['year']];
		if(Report::checkValueOfField($params, 'only_month')) {
			$months = $params['only_month'];
			$columnType = 'months';
		}elseif(Report::checkValueOfField($params, 'quarter_time')){
			$months =  array_map(fn($item) => 'QTR'.$item, $params['quarter_time']);
			$columnType = 'quarters';

		}elseif(Report::checkValueOfField($params, 'half_year')){
			$months = $params['half_year']  === 'start_half_year' ? range(1, 6): range(7,12);
			$columnType = 'months';
		}else {
			$months = $params['year'];
			$columnType = 'years';
		}
		$result['months'] = $months;
		$result['columnType'] = $columnType;
		return $result;
	}

	public function changeDataSource($dataSource, $params)
	{
		$data = [];
		// dd($dataSource);
		foreach ($dataSource as $key => $values) $data[$key] = $values['tableDataSource']['scopes'] ?? [];

		$dataOfMonthOfYear = [];
		$childrenMetrics = [];
		foreach ($data as $year => $values){
			foreach ($values as $scopeId => $items){
				foreach ($items as $ghgTmplId => $items) {
					$dataOfMonthOfYear[$scopeId][$ghgTmplId][$year] = $items;
					$childrenMetrics[$scopeId][$ghgTmplId][$year] = array_map(function($item) {
						if(isset($item['children_metrics'])){
							$arr = $item['children_metrics'];
							return $arr;
						}
					}, $items);
				}
			}
		}
		$dataSet = [];
		// dd($childrenMetrics);

		foreach ($childrenMetrics as $scopeId => &$values) {
			foreach ($values as $ghgTmplId => &$items){
				foreach ($items as $year => $item) {
					// dd($item);
					foreach ($item as $index => $array){
						if(is_null($array)){
							// override data for years without metrics
							$newValues = $this->getValidValueByIndex($items, $index);
							$items[$year][$index] = $newValues;
							continue;
						}
					}
				}
				// dd($items);
				$comparisonData = $this->comparisonData($items);
				$items = $comparisonData;
				$allYear = array_keys($items);
				foreach ($allYear as $y) {
					$arr = $items[$y];
					$newArr = [];
					foreach ($arr as $key => $val) {
						$firstItem = reset($val);
						$commonID = $firstItem['ghg_tmpls_id'] ?? null;
						$newArr[$commonID] = $val;
					}
					$dataSet[$scopeId][$ghgTmplId][$y] = [$ghgTmplId => $newArr];
				}
			}
		}

		// dump($dataSet, $childrenMetrics);
		// make data to build table the same ghg-sheet-050
		$scopeData = $this->makeDataToBuildTable($childrenMetrics);

		$groupByScope = ['scopes' => $scopeData];
		$result['tableDataSource'] = ['scopes' => $scopeData];
		$result['tableSetting'] = $this->createInfoToRenderTable($groupByScope);


		// Adding period to table data to render
		$timeArray = $this->createDateTime($params); 
		$result['timeInfo'] = $timeArray;

		$result['dataSet'] = $dataSet;
		// dd($result);
		return collect($result);
	}


	protected function getDefaultValueParams($params, $request)
	{
		$params['year'] = $this->year;
		return $params;
	}

	public function createInfoToRenderTable($dataSource)
	{
		if(!isset($dataSource['scopes'])) return [];
		$allScopes = $dataSource['scopes'];
		$info = [];
		$totalLine = 2;
		foreach ($allScopes as $k => $items) {
			$num = 0;
			$emptyChildrenMetrics = 0;
			foreach ($items as $values){
				$item = array_filter($values, fn($ele) => !empty($ele))[0] ?? [];
				if(empty($item)) continue;

				$ghgcate_id = $item['ghgcate_id'];
				$countLv2 = Report::countChildrenItemsByKey2($values);
				// dd($countLv2);
				$info[$k][$ghgcate_id]['scope_rowspan_lv2'] = $countLv2;
				
				foreach ($values  as $index => $val){
					if(empty($val)) continue;
					$item = $val['children_metrics'] ?? [];
					$ghg_tmpl_id = $val['ghg_tmpl_id'];
					$info[$k][$ghgcate_id][$ghg_tmpl_id]['scope_rowspan_lv3'] = (count($item) ? count($item) : 1);
					$info[$k][$ghgcate_id][$ghg_tmpl_id]['index_children_metric'] = $index;

					if(isset($val['children_metrics'])) {
						$count = count($val['children_metrics']);
						$num += $count;
					} else{
						$emptyChildrenMetrics += 1;
					}
				}
			}
			$info[$k]['scope_rowspan_lv1'] = $num +$emptyChildrenMetrics;
			$totalLine += $num + $emptyChildrenMetrics;
		}
		$info['total_line'] = $totalLine;
		// dump($info);
		return $info;
	}
}
