<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Ghg_sheet_010;
use App\Http\Controllers\Reports\Reports\Ghg_sheet_dataSource;
use App\Http\Controllers\Reports\TraitConversionFieldNameGhgReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\DocumentReport;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;

class Ghg_sheet_050 extends Report_ParentDocument2Controller
{

	use TraitForwardModeReport;
	use TraitParamsSettingReport;

	protected $viewName = 'document-ghg-sheet-050';
	protected $year = '2023';

	public function getParamColumns($dataSource = [], $modeType = '')
	{
		return [
			[
				'title' => 'Year',
				'dataIndex' => 'year',
			],
			[
				'title' => 'Quarter',
				'dataIndex' => 'quarter_time',
				'allowClear' => true,
				// 'multiple' => true,

			],
			[
				'title' => 'Month',
				'dataIndex' => 'only_month',
				'allowClear' => true,
				'multiple' => true,
				'hasListenTo' => true,
			],
		];
	}

	public function getDataSource($params)
	{
		$primaryData = (new Ghg_sheet_dataSource())->getDataSource($params);
		return collect($primaryData);
	}

	public function changeDataSource($dataSource, $params)
	{
		$dataSource =  Report::convertToType($dataSource);
		$dataSource = DocumentReport::groupMonths($dataSource);
		$months = reset($dataSource)['months'];
		$monthlyTotals = ['sum_total_months' => 0.0] + array_fill_keys(array_keys($months), 0.0);
		foreach ($dataSource as $key => &$item) {
			$monthlyTotals['sum_total_months'] += $item['total_months'];
			foreach ($item['months'] as $month => $value) {
				$monthlyTotals[(string)$month] += (int)$value;
				unset($item[$month]);
			}
			$item['month_ghg_sheet_id'] = StringReport::parseKeyValueString($item['month_ghg_sheet_id']);
		}
		$groupByScope = Report::groupArrayByKey($dataSource, 'scope_id');
		$groupByScope = ['scopes' => array_map(fn ($item) => Report::groupArrayByKey($item, 'ghgcate_id'), $groupByScope)];
		$groupByScope['total_emission'] = $monthlyTotals;
		// dd($groupByScope);



		$ghgReport010 = new Ghg_sheet_010();
		$ghgReport010Data = $ghgReport010->getDataSource($params);
		$ghgReport010Data = $ghgReport010->changeDataSource($ghgReport010Data, $params);
		
		dd($ghgReport010Data, $params);


		return collect($groupByScope);
	}


	protected function getDefaultValueParams($params, $request)
	{
		$params['year'] = $this->year;
		return $params;
	}

	public function createInfoToRenderTable($dataSource)
	{
		$info = [];
		foreach ($dataSource['scopes'] as $k1 => $values1) {
			$array = [];
			$array['scope_rowspan'] = DocumentReport::countLastItems($values1);
			foreach (array_values($values1) as $values2) {
				if (!isset($values2['months'])) continue;
				$array['months'] = array_keys(reset($values2)['months']);
			}
			$info[$k1] = $array;
		}
		return $info;
	}
}
