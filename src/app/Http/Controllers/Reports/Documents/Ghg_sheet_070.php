<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitGenerateValuesFromParamsReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
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


	public function changeDataSource($dataSource, $params)
	{
		$years =  is_array($params['year']) ? $params['year'] : [$params['year']];
		$scopes = $dataSource[last($years)]->toArray()['tableDataSource']['scopes'];
		dd($scopes);
		$existingData = [];
		$dataChildrenMetrics = [];
		foreach ($years as $year) {
			$dt = $dataSource[$year]->toArray()['tableDataSource']['scopes'];
			foreach ($dt as $k1 => $values) {
				foreach ($values as $k2 => $items) {
					$childrenMetrics = array_column($items, 'children_metrics');
					
					$existingData[$year][$k1][$k2]['children_metrics'] = reset($childrenMetrics);
				}
			}
		}
		dd($existingData);
		


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
