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
use App\Utils\Support\NumberReport;
use App\Utils\Support\Report;

class Ghg_sheet_080 extends Report_ParentDocument2Controller
{



	protected $viewName = 'document-ghg-sheet-080';
	protected $year = '2023';
	protected $mode = '080';

	public function getSqlStr($params)
	{
		$sql = "WITH formatted_dates AS (
					SELECT 
						'from_oil' AS key_column,
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
						AND ghgtmp.id IN (1, 4)
						AND ghgmt.id IN (1, 5)
						AND ghgmt1.id IN (1, 14)
						AND ghgmt2.id IN (8, 194)
				)
				SELECT
					key_column,
					SUM(IF(year_only = 2021, total, 0)) AS sum_year_2021,
					SUM(IF(year_only = 2022, total, 0)) AS sum_year_2022,
					SUM(IF(year_only = 2023, total, 0)) AS sum_year_2023,
					SUM(IF(year_only = 2024, total, 0)) AS sum_year_2024,
					SUM(IF(full_date < '2021-07-01', total, 0)) AS sum_first_range_2021,
					SUM(IF(full_date >= '2021-07-01' AND full_date < '2022-01-01', total, 0)) AS sum_second_range_2021,
					SUM(IF(full_date >= '2022-01-01' AND full_date < '2022-07-01', total, 0)) AS sum_first_range_2022,
					SUM(IF(full_date >= '2022-07-01' AND full_date < '2023-01-01', total, 0)) AS sum_second_range_2022,
					SUM(IF(full_date >= '2023-01-01' AND full_date < '2023-07-01', total, 0)) AS sum_first_range_2023,
					SUM(IF(full_date >= '2023-07-01' AND full_date < '2024-01-01', total, 0)) AS sum_second_range_2023,
					SUM(IF(full_date >= '2024-01-01' AND full_date < '2024-07-01', total, 0)) AS sum_first_range_2024,
					SUM(IF(full_date >= '2024-07-01' AND full_date < '2025-01-01', total, 0)) AS sum_second_range_2024
				FROM formatted_dates";
		return $sql;
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
}
