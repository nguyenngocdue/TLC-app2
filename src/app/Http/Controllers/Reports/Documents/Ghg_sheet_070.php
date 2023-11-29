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
use App\Utils\Support\Report;
use Exception;
use Symfony\Component\Yaml\Dumper;

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
				// dump($arr);
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

	private function calculatePercentForYear($dataBefore, $dataAfter) {
		return $dataBefore ? ($dataAfter - $dataBefore)/$dataBefore : $dataAfter;
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
				foreach ($dataMetricAfter as $keyOfChild => $afterData) {
					if(!is_array($afterData) || empty($afterData)) continue;

					// get Id to check
					$dataToCheck = [
						"ghg_tmpls_id" => $afterData['ghg_tmpls_id'],
						"ghg_metric_type_id" => $afterData['ghg_metric_type_id'],
						"ghg_metric_type_1_id" => $afterData['ghg_metric_type_1_id'],
						"ghg_metric_type_2_id" => $afterData['ghg_metric_type_2_id'],
					];
					$_dbIndexBeforeMetric = $this->filterDataInNextYear($dataToCheck, $dbBeforeMetric);
					$dbIndexBeforeMetric = $_dbIndexBeforeMetric ? $_dbIndexBeforeMetric : $afterData;
						if(!empty($dbIndexBeforeMetric)){
							$dbAfterMonth = $_dbIndexBeforeMetric ? $afterData['months'] : array_fill_keys(array_keys($afterData['months']), 0);
							$dbBeforeMonth = $_dbIndexBeforeMetric ? $dbIndexBeforeMetric['months'] : array_fill_keys(array_keys($dbAfterMonth), 0);

							$dbAfterMonthSum = array_sum($dbAfterMonth);
							$dbBeforeMonthSum = array_sum($dbBeforeMonth);

							$numbersSubtract = ArrayReport::subtractArrays($dbBeforeMonth, $dbAfterMonth);
							$array = [
								'meta_subtract_number' =>[
									'months' => $numbersSubtract,
									'years' => $dbAfterMonthSum - $dbBeforeMonthSum,
									'quarters' => $dbAfterMonthSum - $dbBeforeMonthSum
									],
								'meta_percent' => [
									'months' => self::calculatePercentForQuarter($dbBeforeMonth, $dbAfterMonth),
									'years' => $dbBeforeMonthSum ? ($dbAfterMonthSum - $dbBeforeMonthSum)/$dbBeforeMonthSum: null,
									'quarters' => $dbBeforeMonthSum ? ($dbAfterMonthSum - $dbBeforeMonthSum)/$dbBeforeMonthSum: null,
									],
								'meta_normal_number' => [
										'months' => [
											$afterYear => $dbAfterMonth,
											// $beforeYear => $dbBeforeMonth,
										],
										'years' => [
											$afterYear => $dbAfterMonthSum,
											// $beforeYear => $dbBeforeMonthSum,
										],
										'quarters' => [
											$afterYear => $dbAfterMonthSum,
											// $beforeYear => $dbBeforeMonthSum,
										]

									]		
								];
								// dd($afterData);
							$dataRender = [
								$afterYear => [
									'months' => $dbAfterMonth,
									'years' => $afterData['total_months'],
									'quarters' => $dbAfterMonthSum
									]
								];
							$data[$afterYear][$index][$keyOfChild]['comparison_with'/* .$beforeYear */] = $array;
							$data[$afterYear][$index][$keyOfChild]['data_render'] = $dataRender;

							// if($afterYear === 2021) {
							// 	dd($dataMetricAfter, $afterData);
							// }
						} else {
							dd($_dbIndexBeforeMetric);
							$data[$afterYear][$index][$keyOfChild]['data_render'] = [];
							$data[$afterYear][$index][$keyOfChild]['comparison_with'/* .$beforeYear */] = [];
						}
					}
				}
			}
		// dump($data);
		return $data;
	}

	private function makeDataToBuildTable($dataSource) {
		// dd($dataSource);
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
		// dd($result);
		return $result;
	}

	private function createDateTime($params){
		$months = [];
		$result['years'] = is_array($params['year']) ? $params['year'] : [$params['year']];
		if(Report::checkValueOfField($params, 'half_year')){
			$months = $params['half_year']  === 'start_half_year' ? range(1, 6): range(7,12);
			$columnType = 'months';
		}elseif(Report::checkValueOfField($params, 'quarter_time')){
			$months =  array_map(fn($item) => 'QTR'.$item, $params['quarter_time']);
			$columnType = 'quarters';

		} elseif(Report::checkValueOfField($params, 'only_month')) {
			$months = $params['only_month'];
			$columnType = 'months';
		} else {
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
		foreach ($dataSource as $key => $values) $data[$key] = $values['tableDataSource']['scopes'] ?? [];

		$dataOfMonthOfYear = [];
		$childrenMetrics = [];
		foreach ($data as $year => $values){
			foreach ($values as $scopeId => $items){
				foreach ($items as $ghgTmplId => $items) {
					$dataOfMonthOfYear[$scopeId][$ghgTmplId][$year] = $items;
					$childrenMetrics[$scopeId][$ghgTmplId][$year] = array_map(function($item) {
						if(isset($item['children_metrics'])){
							return $item['children_metrics'];
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
							// $newValues['total_months'] = 0;
							// $newValues['months'] = isset($newValues['months']) ?  array_fill_keys(array_keys($newValues['months']), '') : [];
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
