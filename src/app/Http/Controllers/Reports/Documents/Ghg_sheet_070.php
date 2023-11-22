<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitGenerateValuesFromParamsReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\ArrayReport;
use App\Utils\Support\DateReport;
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
				$currentData = $data[$currentYear][$index];
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
					$data[$currentYear][$index] = $currentData;
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
					$data[$nextYear][$index] = $nextData;
				}
			}
		}
		return $data;
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
							return $item['children_metrics'][0];
						}
					}, $items);
				}
			}
		}
		// dd($childrenMetrics);

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
				$items = $comparisonData;
			}
		}



		dump($childrenMetrics);



		return $dataSource;
	}


	protected function getDefaultValueParams($params, $request)
	{
		$params['year'] = $this->year;
		return $params;
	}


	public function createInfoToRenderTable($dataSource)
	{
		if(!isset($dataSource['scopes'])) return [];
		$info = [];
		$totalLine = 2;
		foreach ($dataSource['scopes'] as $k => $items) {
			$num = 0;
			$emptyChildrenMetrics = 0;
			foreach ($items as $values){
				$item = last($values);
				$ghgcate_id = $item['ghgcate_id'];
				$countLv2 = Report::countChildrenItemsByKey($values);
				$info[$k][$ghgcate_id]['scope_rowspan_lv2'] = $countLv2;
				foreach ($values  as $index => $val){
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
