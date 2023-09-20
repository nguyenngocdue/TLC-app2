<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Ghg_sheet_dataSource;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\DocumentReport;
use App\Utils\Support\Report;
use Illuminate\Support\Str;


class Ghg_sheet_030 extends Report_ParentDocument2Controller
{

	use TraitForwardModeReport;
	use TraitParamsSettingReport;

	protected $viewName = 'document-ghg-summary-report-030-040';
	protected $year = '2023';
	protected $mode = '030';
	protected $typeTime = 'quarters';


	public function getParamColumns($dataSource = [], $modeType = '')
	{
		return [
			[
				'title' => 'Year',
				'dataIndex' => 'year',
				'multiple' => true,
			],
			[
				'title' => 'Quarter',
				'dataIndex' => 'quarter_time',
				'allowClear' => true,
				'multiple' => true,

			],

		];
	}

	public function getDataSource($params)
	{
		$dataSource = [];
		$years =  $params['year'];
		sort($years);
		foreach ($years as $year) {
			$params['year'] = $year;
			$primaryData = (new Ghg_sheet_dataSource())->getDataSource($params)->toArray();
			$dataSource[$year] = $primaryData;
		}
		return collect($dataSource);
	}

	public function changeDataSource($dataSource, $params)
	{
		$fieldsTime = array_map(fn ($item) =>Str::singular($this->typeTime) . $item, $params['quarter_time']);
		$groupByScope = $this->makeDataByTypeTime($fieldsTime, $dataSource, $this->typeTime);
		return collect($groupByScope);
	}


	protected function getDefaultValueParams($params, $request)
	{
		$a = 'year';
		if (Report::isNullParams($params)) {
			$params[$a] = $this->year;
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
