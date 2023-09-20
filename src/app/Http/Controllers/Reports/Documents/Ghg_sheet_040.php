<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Ghg_sheet_dataSource;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\DocumentReport;
use App\Utils\Support\Report;
use Illuminate\Support\Str;


class Ghg_sheet_040 extends Report_ParentDocument2Controller
{

	use TraitForwardModeReport;
	use TraitParamsSettingReport;

	protected $viewName = 'document-ghg-summary-report-030-040';
	protected $year = '2023';
	protected $mode = '040';
	protected $typeTime = 'months';

	public function getParamColumns($dataSource = [], $modeType = '')
	{
		return [
			[
				'title' => 'Year',
				'dataIndex' => 'year',
				'multiple' => true,
			],
			[
				'title' => 'Month',
				'dataIndex' => 'only_month',
				'allowClear' => true,
				'multiple' => true,

			],

		];
	}

	public function getDataSource($params)
	{
		$dataSource = [];
		$years =  $params['year'];
		foreach ($years as $year) {
			$params['year'] = $year;
			$primaryData = (new Ghg_sheet_dataSource())->getDataSource($params)->toArray();
			$dataSource[$year] = $primaryData;
		}
		// dd($dataSource);
		return collect($dataSource);
	}

	public function changeDataSource($dataSource, $params)
	{
		$fieldsTime = array_map(
			fn ($item) => $item = strlen($item) < 2 ? '0' . $item : $item,
			$params['only_month']
		);
		$groupByScope = $this->makeDataByTypeTime($fieldsTime, $dataSource, $this->typeTime);
		return collect($groupByScope);
	}


	protected function getDefaultValueParams($params, $request)
	{
		$a = 'year';
		$b = 'only_month';
		if (Report::isNullParams($params)) {
			$params[$a] = [2021,2022,2023];
			$params[$b] = ['01', '02', '03', '04'];
		}
		return $params;
	}

	public function createInfoToRenderTable($dataSource)
	{
		if (empty($dataSource->toArray())) return [];
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
