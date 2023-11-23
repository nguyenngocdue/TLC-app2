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
		foreach($data as $key => $items){
			if(Report::checkValueOfField($items, $index)) {
				return $items[$index];
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

	private function comparisonData($data) {
		$years = array_keys($data);
		$year = last($years);
		$countLines = count($data[$year]);

		for ($i=0; $i < count($years); $i++) { 
			for ($index=0; $index < $countLines; $index++) {
				// data 1
				$currentYear = $years[$i]; 
				$childrenMetrics = $data[$currentYear][$index];
				
				// set comparison's values for metric2
				try {
					foreach ($childrenMetrics as $keyOfChild => $item) {
						if(!is_array($item)) continue;
						$currentData = $item;
						$month1 = $currentData['months'];
	
						// data 2
						if($i === 0) {
							// add data for the first item
							$currentData['data_'.$currentYear-1] = ['month' => $month1];
							$quarterFromCurrentData = DateReport::calculateQuarterTotals($month1);
							$currentData['comparison_with_'.$currentYear-1] = [
								'meta_number' =>[
											'month' => $month1,
											'quarter' => $quarterFromCurrentData,
											'year' => [$currentYear-1 => array_sum(array_values($month1))],
								],
								'meta_percent' => [
											'month' => array_fill_keys(array_keys($month1), 0),
											'quarter' => array_fill_keys(array_keys($quarterFromCurrentData), 0),
											'year' => [$currentYear-1 => 0]
								]	
								];
							$data[$currentYear][$index][$keyOfChild] = $currentData;
						}
						if(isset($years[$i+1])) {
							// add data for the second item
							$nextYear = $years[$i+1];
							$nextData = $data[$nextYear][$index];	
							$month2 = $nextData['months'];
							//subtract data 1 - data 2
							$diff = ArrayReport::subtractArrays($month1, $month2);
							$nextData['data_'.$currentYear] = ['month' => $month1];
							$nextData['data_'.$nextYear] = ['month' => $month2];
							
							$quarterBefore = DateReport::calculateQuarterTotals($month1);
							$quarterFromNextData = DateReport::calculateQuarterTotals($diff);
		
							$yearBefore = array_sum(array_values($month1));
							$yearAfter = array_sum(array_values($diff));
		
							$nextData['comparison_with_'.$currentYear] = [
								'meta_number' => [
									'month' => $diff,
									'quarter' => $quarterFromNextData,
									'year' => [$currentYear => array_sum(array_values($diff))],
								],
								'meta_percent' => [
									'month' => self::calculatePercentForQuarter($month1, $diff),
									'quarter' => self::calculatePercentForQuarter($quarterBefore, $quarterFromNextData),
									'year' => [$currentYear =>  self::calculatePercentForYear($yearBefore, $yearAfter)],
								]
							];
							$data[$nextYear][$index][$keyOfChild] = $nextData;
						}
					}
				} catch(Exception $e) {
					// dd($e->getMessage(), $item);
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
					$arr1[$k] = $arr;
				}
				$result[$scopeId][$ghgCatId] = $arr1;
			}
		}
		// dd($result['335']);
		return $result;
	}

	public function changeDataSource($dataSource, $params)
	{
		// dd($dataSource);
		$years =  is_array($params['year']) ? $params['year'] : [$params['year']];
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

		foreach ($childrenMetrics as $scopeId => &$values) {
			foreach ($values as $ghgTmplId => &$items){
				foreach ($items as $year => $item) {
					foreach ($item as $index => $array){
						if(is_null($array)){
							// override data for years without metrics
							$newValues = $this->getValidValueByIndex($items, $index);
							$newValues['total_months'] = 0;
							$newValues['months'] = isset($newValues['months']) ?  array_fill_keys(array_keys($newValues['months']), '') : [];
							$items[$year][$index] = $newValues;
						}
					}
				}
				$comparisonData = $this->comparisonData($items);
				// dd($comparisonData);
				$items = $comparisonData;
			}
		}

		// make data to build table the same ghg-sheet-050
		$scopeData = $this->makeDataToBuildTable($childrenMetrics);
		// dd($scopeData);

		$groupByScope = ['scopes' => $scopeData];
		$result['tableDataSource'] = ['scopes' => $scopeData];
		$result['tableSetting'] = $this->createInfoToRenderTable($groupByScope);

		// dump($result);
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
		dump($allScopes);
		$info = [];
		$totalLine = 2;
		foreach ($allScopes as $k => $items) {
			$num = 0;
			$emptyChildrenMetrics = 0;
			foreach ($items as $values){
				$item = last($values);
				if(empty($item)) continue;
				$ghgcate_id = $item['ghgcate_id'];
				$countLv2 = Report::countChildrenItemsByKey($values);
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
		// dump($info, $dataSource);
		return $info;
	}
}
