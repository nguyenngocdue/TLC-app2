<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitGenerateValuesFromParamsReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Models\Ghg_cat;
use App\Models\Ghg_metric_type;
use App\Models\Ghg_metric_type_1;
use App\Models\Ghg_metric_type_2;
use App\Models\Term;
use App\Utils\Support\ArrayReport;
use App\Utils\Support\DateReport;
use App\Utils\Support\NumberReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Ghg_sheet_080 extends Report_ParentDocument2Controller
{



	protected $viewName = 'document-ghg-sheet-080';
	protected $year = '2023';
	protected $mode = '080';


	private function configForStringSQL()
	{
		$ghgMetricTypeIds = implode(",", Ghg_metric_type::get()->pluck('id')->toArray());
		$ghgMetricType1s = implode(",", Ghg_metric_type_1::get()->pluck('id')->toArray());
		$ghgMetricType2s = implode(",", Ghg_metric_type_2::get()->pluck('id')->toArray());

		return [
			"from_oil" =>  [
				"field_type" => "from_oil",
				"ghgtmp" => [1, 4],
				'ghgmt' => [1, 5],
				'ghgmt1' => [1, 14],
				'ghgmt2' => [8, 194],
			],
			"from_natural_gas" =>  [
				"field_type" => "from_natural_gas",
				"ghgtmp" => [1],
				'ghgmt' => [1],
				'ghgmt1' => [1],
				'ghgmt2' => [4],
			],
			"from_other_please specify non_renewable_fuel_type" =>  [
				"field_type" => "from_other_please specify non_renewable_fuel_type",
				"ghgtmp" => [1],
				'ghgmt' => [1],
				'ghgmt1' => [1],
				'ghgmt2' => [9],
			],
			"electricity_consumption_from_non_renewable_energy" =>  [
				"field_type" => "electricity_consumption_from_non_renewable_energy",
				"ghgtmp" => [5],
				'ghgmt' => [7],
				'ghgmt1' => [23],
				'ghgmt2' => [231],
			],
			"electricity_consumption_from_non_renewable_energy" =>  [
				"field_type" => "electricity_consumption_from_non_renewable_energy",
				"ghgtmp" => [8],
				'ghgmt' => [$ghgMetricTypeIds],
				'ghgmt1' => [$ghgMetricType1s],
				'ghgmt2' => [$ghgMetricType2s],
			],
			"waste_diverted_from_disposal" =>  [
				"field_type" => "waste_diverted_from_disposal",
				"ghgtmp" => [8],
				'ghgmt' => [17],
				'ghgmt1' => [69],
				'ghgmt2' => [279],
			],
			"total_water_consumption" =>  [
				"field_type" => "total_water_consumption",
				"ghgtmp" => [6],
				'ghgmt' => [8],
				'ghgmt1' => [24],
				'ghgmt2' => [232],
			]
		];
	}


	private function generateStringCondition1($items)
	{
		$strSQL = "";
		try {
			$ghgTmplId = '(' . implode(",", $items['ghgtmp']) . ')';
			$ghgMetricTypeId = '(' . implode(",", $items['ghgmt']) . ')';
			$ghgMetricType1Id = '(' . implode(",", $items['ghgmt1']) . ')';
			$ghgMetricType2Id = '(' . implode(",", $items['ghgmt2']) . ')';
			$strSQL .= "\n AND ghgtmp.id IN " . $ghgTmplId;
			$strSQL .= "\n AND ghgmt.id IN " . $ghgMetricTypeId;
			$strSQL .= "\n AND ghgmt1.id IN " . $ghgMetricType1Id;
			$strSQL .= "\n AND ghgmt2.id IN " . $ghgMetricType2Id;
		} catch (\Exception $e) {
			dd($items);
		}
		return $strSQL;
	}

	private function generateStringCondition2($years)
	{
		$strSQL = "";
		foreach ($years as $year) {
			$strSQL .= "\n SUM(IF(year_only = {$year}, total, 0)) AS sum_year_{$year},";
			$strSQL .= "\n SUM(IF(full_date >= '{$year}-01-01' AND full_date < '{$year}-07-01', total, 0)) AS sum_first_range_{$year}, 
						\n SUM(IF(full_date >= '{$year}-07-01' AND full_date <= '{$year}-12-31', total, 0)) AS sum_second_range_{$year},";
		}
		return $strSQL;
	}


	private function generateStringSQL($years)
	{
		$dataConfig = $this->configForStringSQL();
		// string of field columns
		$strCondition2 = trim($this->generateStringCondition2([2021, 2022, 2023]), ',');
		$arrayStrSQL = [];
		foreach ($dataConfig as $key => $items) {
			$strCondition1 = $this->generateStringCondition1($items);
			$sqlStr = "WITH formatted_dates AS (
						SELECT
						    '{$key}' AS field_column,
							ghgtmp.name AS tmpl_name,
							ghgmt1.name AS metric_type_1_name,
							ghgmt2.name AS metric_type_2_name,
							ghgsl.total,
							ghgs.ghg_month,
							DATE_FORMAT(ghgs.ghg_month, '%Y') AS year_only,
							DATE_FORMAT(ghgs.ghg_month, '%Y-%m-%d') AS full_date
						FROM ghg_tmpls ghgtmp
						LEFT JOIN ghg_metric_types ghgmt ON ghgmt.ghg_tmpl_id = ghgtmp.id
						LEFT JOIN ghg_metric_type_1s ghgmt1 ON ghgmt1.ghg_metric_type_id = ghgmt.id
						LEFT JOIN ghg_metric_type_2s ghgmt2 ON ghgmt2.ghg_metric_type_1_id = ghgmt1.id
						LEFT JOIN ghg_sheets ghgs ON ghgs.ghg_tmpl_id = ghgtmp.id
						LEFT JOIN ghg_sheet_lines ghgsl ON ghgsl.ghg_sheet_id = ghgs.id 
							AND ghgsl.ghg_tmpl_id = ghgtmp.id
							AND ghgsl.ghg_metric_type_id = ghgmt.id
							AND ghgsl.ghg_metric_type_1_id = ghgmt1.id
							AND ghgsl.ghg_metric_type_2_id = ghgmt2.id
						WHERE 1 = 1
							{$strCondition1}
					)
					SELECT
						field_column,
						{$strCondition2}
					FROM formatted_dates";
			$arrayStrSQL[$key] = $sqlStr;
		}
		return $arrayStrSQL;
	}




	protected function getParamColumns($dataSource, $modeType)
	{
		return [
			[]
		];
	}

	public function getTableColumns($params, $dataSource)
	{
		return
			[
				[]
			];
	}

	private function generateDataSource($params)
	{
		$dataStrSQL = $this->generateStringSQL($params);
		$dataOutput = [];
		foreach ($dataStrSQL as $key => $strSQL) {
			$dataQuery = DB::select($strSQL);
			$dataOutput[$key] = $dataQuery;
		}
		dd($dataOutput);
	}

	public function getDataSource($params)
	{
		return $this->generateDataSource($params);
	}
}
