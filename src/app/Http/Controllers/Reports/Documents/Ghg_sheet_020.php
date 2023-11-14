<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Ghg_sheet_dataSource;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Models\Term;
use App\Utils\Support\DocumentReport;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;
use Illuminate\Support\Facades\DB;

class Ghg_sheet_020 extends Report_ParentDocument2Controller
{

	use TraitForwardModeReport;
	use TraitParamsSettingReport;

	protected $viewName = 'document-ghg-co2-report';
	protected $year = '2023';
	protected $mode = '020';
	protected $type = 'ghg_sheets';
	protected $optionPrint = "landscape";

	public function getParamColumns($dataSource, $modeType)
	{
		return [
			[
				'title' => 'Year',
				'dataIndex' => 'year',
			],
			[
				'title' => 'Half Year',
				'dataIndex' => 'half_year',
				// 'multiple' => true,
				'hasListenTo' => true,
				'allowClear' => true,
			],
			[
				'title' => 'Quarter',
				'dataIndex' => 'quarter_time',
				'multiple' => true,
				'allowClear' => true,
			],
			[
				'title' => 'Month',
				'dataIndex' => 'only_month',
				'multiple' => true,
				'allowClear' => true,
			],
		];
	}

	private function getNumberOfUserOfYear($params)
	{
		$year = $params['year'];
		$sql =  "SELECT all_year_tb.*, ROUND( (";
		for ($i = 1; $i <= 12; $i++) {
			$n = strlen($i) < 2 ? '0' . $i : $i;
			$sql .= "all_year_tb.`" . ($i === 12 ? $n . '` ) / 12,2) AS avg_user' : $n . '`+ ');
		}
		$sql .= "\n FROM ( SELECT '$year' AS year,";
		for ($i = 1; $i <= 12; $i++) {
			$n = strlen($i) < 2 ? '0' . $i : $i;
			$strNum = $i === 12 ? "`$n`" : "`$n`,";
			$yearMonth = "$year-$n";
			$sql .= "\n COUNT(DISTINCT IF(SUBSTR(us.first_date, 1, 7) <= '$yearMonth' AND us.last_date IS NULL AND us.resigned = 0, us.id,
			IF(SUBSTR(us.first_date, 1, 7) <= '$yearMonth' AND us.last_date IS NOT NULL AND SUBSTR(us.last_date, 1, 7) >= '$yearMonth' AND us.resigned = 1, us.id, NULL)
		)) AS $strNum";
		}
		$sql .= "\n FROM users us
						WHERE 1 = 1
						AND us.first_date IS NOT NULL
						AND SUBSTR(us.first_date, 1, 4) <= $year) AS all_year_tb";

		$sqlData = DB::select(DB::raw($sql));
		$collection = collect($sqlData);
		return $collection;
	}

	public function getDataSource($params)
	{
		// dd($params);
		$primaryData = (new Ghg_sheet_dataSource())->getDataSource($params);
		return collect($primaryData);
	}

	private function updateDataForPivotChart($data, $key)
	{
		$array = [];
		$max = 0;
		$count = 1;
		$scopeData = $data[$key];
		$scopeNames = [];
		foreach ($scopeData as $key => $scope) {
			$scopeName = Term::find($scope['scope_id'])->toArray()['name'];
			$scopeNames[] = [
				'id' => $scope['scope_id'],
				'name' => $scopeName,
			];
			$array['meta']['labels'][] = $scopeName;
			$array['meta']['numbers'][] = $scope['total_tco2e'];
			$array['meta']['max'] =  $max + $scope['total_tco2e'];
			$array['meta']['count'] = $count++;
			$array['metric'][] = (object)[
				'metric_id' => $scope['scope_id'],
				'metric_name' => $scopeName,
				'metric_count' => $scope['total_tco2e']
			];
			$max  = $max + $scope['total_tco2e'];
		}
		foreach ($array as $key => &$value) {
			if ($key === 'meta') {
				$value['labels'] = StringReport::arrayToJsonWithSingleQuotes($value['labels']);
				$value['numbers'] = $value['numbers'];
			}
		}
		$array['dimensions'] = [
			'showLegend' => true,
		];
		$array['scopeNames'] = $scopeNames;
		$data['pivot_chart_1'] = $array;
		// dd($data);
		return $data;
	}

	private function updateDataPivotHorizontalChart($dataSource)
	{
		$scopes = $dataSource['scopes'];
		foreach ($scopes as $scopeId => $scope) $data[$scopeId] = array_merge(...$scope);
		$datasets = [];
		$numbers = array_fill(0, count($data), 0);
		$labels = [];
		$index = 0;
		foreach ($data as $scopeId => $items) {
			if (is_null($items)) continue;
			foreach ($items as $value) {
				if (!$value['ghgtmpl_name']) continue;
				$labels[$scopeId] = $value['scope_name'];
				$val = $numbers;
				$val[$index] = is_null($value['total_months']) ? 0 : $value['total_months'];
				// $val = StringReport::arrayToJsonWithSingleQuotes($val);
				$datasets[] = (object)[
					'label' => $value['ghgtmpl_name'],
					'data' =>  $val,
				];
			}
			++$index;
		}
		$metric  = [];
		$dataSource->put('pivot_chart_2', []);
		$dataSource['pivot_chart_2'] = [
			'meta' => [
				'numbers' => $datasets,
				'labels' => StringReport::arrayToJsonWithSingleQuotes($labels),
				'count' => count($datasets),
				'max' => 1,
			],
			'metric' => $metric,
		];
		return $dataSource;
	}

	private function addInfo($dataSource)
	{
		// dd($dataSource);
		if (isset($dataSource['tco2e_by_scope'])) {
			$directEmissions = Report::filterItemByKeyAndValue($dataSource['tco2e_by_scope'], 'scope_id', 335)[0]['total_tco2e'] ?? null;
			$inDirectEmissions = Report::filterItemByKeyAndValue($dataSource['tco2e_by_scope'], 'scope_id', 336)[0]['total_tco2e'] ?? null;
			$otherInDirectEmissions = Report::filterItemByKeyAndValue($dataSource['tco2e_by_scope'], 'scope_id', 337)[0]['total_tco2e'] ?? null;

			$max = $dataSource['pivot_chart_1']['meta']['max'];
			$array = [
				'direct_emissions' => [
					'tco2e' => $directEmissions,
					'percent' => $max ? round($directEmissions * 100 / $max, 2) : null
				],
				'indirect_emissions' => [
					'tco2e' => $inDirectEmissions,
					'percent' => $max ? round($inDirectEmissions * 100 / $max, 2) : null
				],
				'other_indirect_emissions' => [
					'tco2e' => $otherInDirectEmissions,
					'percent' => $max ? round($otherInDirectEmissions * 100 / $max, 2) : null
				]
			];
			$dataSource->put('info', $this->addInfo($array));
			// dd($dataSource);
		}
		return $dataSource;
	}

	public function changeDataSource($dataSource, $params)
	{
		// DataSource for Ghg_sheet_010
		$report010 = new Ghg_sheet_010();
		$dataSource010 = $report010->getDataSource($params);
		$dataSource010 = $report010->changeDataSource($dataSource010, $params);
		// dd($dataSource010);


		// DataSource for GHGRP Basin Production & Emissions
		$report040 = new Ghg_sheet_040();
		$dataSource040 = $report040->getDataSource($params);
		// dd($dataSource040);


		$dataSource =  Report::convertToType($dataSource);
		$data = (new Ghg_sheet_010())->changeDataSource($dataSource, $params);
		$c02FootprintInfo = (array)$this->getNumberOfUserOfYear($params)->toArray();
		$c02FootprintInfo = Report::transformDataItemByKey($c02FootprintInfo, 'year');

		// Calculate Scopes
		$scopeSummaryMonths = [];
		foreach ($data['scopes'] as $key => $value) {
			if (!is_numeric($key)) continue;
			$scopeSummaryMonths[] = [
				'scope_id' => $key,
				'total_tco2e' => round(array_sum(Report::getLastArrayValuesByKey($value, 'total_months')), 2)
			];
		}

		$totalEmission = $data['total_emission']['sum_total_months'];
		$year = $params['year'];
		//add the Carbon footprint ratio per employee
		$c02FootprintInfo[$year]['co2_footprint_employee'] = round($totalEmission / $c02FootprintInfo[$year]['avg_user'], 2);
		//add total the Company Carbon Footprint emissions
		$c02FootprintInfo[$year]['total_emission'] = $data['total_emission']['sum_total_months'];

		$data->put('carbon_footprint', $c02FootprintInfo);
		$data->put('tco2e_by_scope', $scopeSummaryMonths);
		// update values to render pivot chart
		$data = self::updateDataForPivotChart($data, 'tco2e_by_scope');
		$data = self::updateDataPivotHorizontalChart($data);
		$data = self::addInfo($data);
		$data->put('document_ghg_sheet_010', $dataSource010);


		//document_ghg_sheet_050
		$report050 = new Ghg_sheet_050();
		$dataSource050 = $report050->getDataSource($params);
		$dataSource050 = $report050->changeDataSource($dataSource050, $params);
		$data->put('document_ghg_sheet_050', $dataSource050);


		//document_ghg_sheet_040
		$report040 = new Ghg_sheet_040();
		$dataSource040 = $report040->getDataSource($params);
		$dataSource040 = $report040->changeDataSource($dataSource040, $params);
		$data->put('document_ghg_sheet_040', $dataSource040);
		return collect($data);
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
				if (is_array($values2) && !isset($values2['months'])) continue;
				$array['months'] = array_keys(reset($values2)['months']);
			}
			$info[$k1] = $array;
		}
		return $info;
	}
}
