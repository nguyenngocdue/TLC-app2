<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitConversionFieldNameGhgReport;
use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitGenerateValuesFromParamsReport;
use App\Utils\Support\DateReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Ghg_sheet_dataSource extends Controller

{
	use TraitDynamicColumnsTableReport;
	use TraitCreateSQL;
	use TraitConversionFieldNameGhgReport;
	use TraitGenerateValuesFromParamsReport;
	protected $year = '2023';


	public function getSqlStr($params)
	{
		[$start_date, $end_date, $year, $strSqlMonth, $strSumValue] = $this->createValuesForDateParam($params);
		$sql =  " SELECT infghgsh.*,$strSqlMonth ghgsh_totals.month_ghg_sheet_id,
					    ghgsh_totals.quarter1,
						ghgsh_totals.quarter2,
						ghgsh_totals.quarter3,
						ghgsh_totals.quarter4,
						ROUND($strSumValue,3) AS total_months,
						ROUND($strSumValue,3) AS `$year`,
						ROUND($strSumValue,3) AS 'year'
						FROM (SELECT 
						term.id AS scope_id, term.name AS scope_name,
						ghgcate.id AS ghgcate_id, ghgcate.name AS ghgcate_name,
						ghgtmpl.id AS ghg_tmpl_id, ghgtmpl.name AS ghgtmpl_name
						FROM ghg_cats ghgcate
						LEFT JOIN terms term ON ghgcate.scope_id = term.id
						LEFT JOIN ghg_tmpls ghgtmpl ON ghgtmpl.ghg_cat_id = ghgcate.id
						ORDER BY ghgcate_name, ghgtmpl_name) infghgsh
						LEFT JOIN (
							SELECT
							ghgsh.ghg_tmpl_id AS ghgsh_tmpl_id,
							GROUP_CONCAT(CONCAT(SUBSTR(ghgsh.ghg_month, 6, 2),':',ghgsh.id)) AS month_ghg_sheet_id,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '01' THEN ghgsh.total ELSE 0 END)/1000,3) AS `01`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '02' THEN ghgsh.total ELSE 0 END)/1000,3) AS `02`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '03' THEN ghgsh.total ELSE 0 END)/1000,3) AS `03`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '04' THEN ghgsh.total ELSE 0 END)/1000,3) AS `04`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '05' THEN ghgsh.total ELSE 0 END)/1000,3) AS `05`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '06' THEN ghgsh.total ELSE 0 END)/1000,3) AS `06`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '07' THEN ghgsh.total ELSE 0 END)/1000,3) AS `07`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '08' THEN ghgsh.total ELSE 0 END)/1000,3) AS `08`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '09' THEN ghgsh.total ELSE 0 END)/1000,3) AS `09`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '10' THEN ghgsh.total ELSE 0 END)/1000,3) AS `10`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '11' THEN ghgsh.total ELSE 0 END)/1000,3) AS `11`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '12' THEN ghgsh.total ELSE 0 END)/1000,3) AS `12`,
							ROUND(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) IN ('01', '02', '03') THEN ghgsh.total ELSE 0 END) / 1000, 3) AS quarter1,
        					ROUND(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) IN ('04', '05', '06') THEN ghgsh.total ELSE 0 END) / 1000, 3) AS quarter2,
        					ROUND(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) IN ('07', '08', '09') THEN ghgsh.total ELSE 0 END) / 1000, 3) AS quarter3,
        					ROUND(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) IN ('10', '11', '12') THEN ghgsh.total ELSE 0 END) / 1000, 3) AS quarter4
							FROM ghg_sheets ghgsh 
							WHERE 1 = 1
							AND ghgsh.deleted_by IS NULL
							AND SUBSTR(ghgsh.ghg_month, 1, 4) = '$year'";
		if (isset($params['half_year']) && $start_date) $sql .= " \n AND SUBSTR(ghgsh.ghg_month, 1, 10) >= '$start_date'";
		if (isset($params['half_year']) && $end_date) $sql .= " \n AND SUBSTR(ghgsh.ghg_month, 1, 10) <= '$end_date'";

		$sql .= "\n GROUP BY ghgsh_tmpl_id
						) ghgsh_totals ON infghgsh.ghg_tmpl_id = ghgsh_totals.ghgsh_tmpl_id
						ORDER BY ghgcate_id, ghg_tmpl_id
					";
		// dump($sql);
		return $sql;
	}
	public function getDataSource($params)
	{
		$sql = $this->getSql($params);
		if (is_null($sql) || !$sql) return collect();
		$sqlData = DB::select($sql);
		$collection = collect($sqlData);
		$collection = $this->convertNames($collection);
		return $collection;
	}
}
