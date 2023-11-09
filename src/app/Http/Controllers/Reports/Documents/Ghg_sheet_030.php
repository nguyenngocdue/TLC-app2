<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Ghg_sheet_dataSource;
use App\Http\Controllers\Reports\TraitConversionFieldNameGhgReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\DocumentReport;
use App\Utils\Support\Report;
use Illuminate\Support\Str;


class Ghg_sheet_030 extends Report_ParentDocument2Controller
{

	use TraitForwardModeReport;
	use TraitParamsSettingReport;

	protected $viewName = 'document-ghg-summary-report-030';
	protected $year = '2023';
	protected $mode = '030';


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
			[
				'title' => 'Month',
				'dataIndex' => 'only_month',
				'allowClear' => true,
				'multiple' => true,
				// 'hasListenTo' => true,
			]

		];
	}

	public function getDataSource($params)
	{
		$dataSource = [];
		$years =  is_array($params['year']) ? $params['year'] : [$params['year']];
		$valueOfYears = [];
		foreach ($years as $year) {
			$params['year'] = $year;
			$primaryData = (new Ghg_sheet_dataSource())->getDataSource($params)->toArray();
			$valueOfYears[$year] = array_column(array_values($primaryData), $year);
			$dataSource[$year] = $primaryData;
		}

				
		return collect($dataSource);
	}

	public function changeDataSource($dataSource, $params)
	{
		$groupByScope = [];
		if (isset($params['only_month'])) {
			$fieldsTime = array_map(
				fn ($item) => $item = strlen($item) < 2 ? '0' . $item : $item,
				$params['only_month']
			);
			$groupByScope = $this->makeDataByTypeTime($fieldsTime, $dataSource, 'months');
		} elseif (isset($params['quarter_time'])) {
			$fieldsTime = array_map(fn ($item) => Str::singular('quarters') . $item, $params['quarter_time']);
			$groupByScope = $this->makeDataByTypeTime($fieldsTime, $dataSource, 'quarters');
		} else {
			$fieldsTime = array_map(fn ($item) =>  $item, $params['year']);
			$groupByScope = $this->makeDataByTypeTime($fieldsTime, $dataSource, 'years');
			// dd($groupByScope);
		}
		// dd(collect($groupByScope));
		return collect($groupByScope);
	}


	protected function getDefaultValueParams($params, $request)
	{
		$params['year'] = [2023];
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
