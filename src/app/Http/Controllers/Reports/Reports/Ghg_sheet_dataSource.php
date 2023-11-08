<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Utils\Support\DateReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Ghg_sheet_dataSource extends Controller

{
    use TraitDynamicColumnsTableReport;
    use TraitCreateSQL;
    protected $year = '2023';

	public function getSqlStr($params)
	{
		// GET MONTHS TO SHOW ON TABLE
		$months = range(1, 12);
		if(Report::checkValueOfField($params, 'half_year')){
			$months = $params['half_year']  === 'start_half_year' 
						? range(1, 6): range(7,12);
		}
		if(Report::checkValueOfField($params, 'quarter_time')){
			$quarterTimes = $params['quarter_time'];
			$months = array_merge(...array_map(fn($item) => DateReport::getMonthsByQuarter($item), $quarterTimes));
		}


		if (isset($params['quarter_time'])) {
			$quarter = $params['quarter_time'];
			if ($quarter === '1') {
				$months = range(1, 3);
			} elseif ($quarter === '2') {
				$months = range(4, 6);
			} elseif ($quarter === '3') {
				$months = range(7, 9);
			} elseif ($quarter === '4') {
				$months = range(10, 12);
			}
		}
		if (isset($params['only_month'])) {
			$months = $params['only_month'];
			if (is_null($params['only_month'][0])) {
				$months = range(1, 12);
			}
		}
		$strSqlMonth = '';
		foreach ($months as $month) {
			$tableName = 'ghgsh_totals';
			$month = strlen($month) > 1 ? $month : '0' . $month;
			$strSqlMonth .= $tableName . '.' . $month . ',';
		}
		$strSumValue = str_replace(',', ' + ', trim($strSqlMonth, ','));
		$year = $params['year'];
		// dd($strSqlMonth, $strSumValue);

		// Set half year
		$start_date = $year.'-01-01';
		$end_date = $year.'-12-31';
		if(isset($params['half_year']) && $params['year']) {
			$key = $params['half_year'];
			$strDate = DateReport::getHalfYearPeriods($params['year'])[$key];
			[$start_date, $end_date] = explode('/',$strDate);
		}
		// SQL
		$sql =  " SELECT infghgsh.*,$strSqlMonth ghgsh_totals.month_ghg_sheet_id,
					    ghgsh_totals.quarter1,
						ghgsh_totals.quarter2,
						ghgsh_totals.quarter3,
						ghgsh_totals.quarter4,
						ROUND($strSumValue,2) AS total_months,
						ROUND($strSumValue,2) AS `$year`
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
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '01' THEN ghgsh.total ELSE 0 END)/1000,2) AS `01`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '02' THEN ghgsh.total ELSE 0 END)/1000,2) AS `02`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '03' THEN ghgsh.total ELSE 0 END)/1000,2) AS `03`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '04' THEN ghgsh.total ELSE 0 END)/1000,2) AS `04`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '05' THEN ghgsh.total ELSE 0 END)/1000,2) AS `05`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '06' THEN ghgsh.total ELSE 0 END)/1000,2) AS `06`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '07' THEN ghgsh.total ELSE 0 END)/1000,2) AS `07`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '08' THEN ghgsh.total ELSE 0 END)/1000,2) AS `08`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '09' THEN ghgsh.total ELSE 0 END)/1000,2) AS `09`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '10' THEN ghgsh.total ELSE 0 END)/1000,2) AS `10`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '11' THEN ghgsh.total ELSE 0 END)/1000,2) AS `11`,
							round(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '12' THEN ghgsh.total ELSE 0 END)/1000,2) AS `12`,
							ROUND(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) IN ('01', '02', '03') THEN ghgsh.total ELSE 0 END) / 1000, 2) AS quarter1,
        					ROUND(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) IN ('04', '05', '06') THEN ghgsh.total ELSE 0 END) / 1000, 2) AS quarter2,
        					ROUND(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) IN ('07', '08', '09') THEN ghgsh.total ELSE 0 END) / 1000, 2) AS quarter3,
        					ROUND(SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) IN ('10', '11', '12') THEN ghgsh.total ELSE 0 END) / 1000, 2) AS quarter4
							FROM ghg_sheets ghgsh 
							WHERE 1 = 1
							AND ghgsh.deleted_by IS NULL
							AND SUBSTR(ghgsh.ghg_month, 1, 4) = '$year'";
				if(isset($params['half_year']) && $start_date) $sql .=" \n AND SUBSTR(ghgsh.ghg_month, 1, 10) >= '$start_date'";
				if(isset($params['half_year']) && $end_date) $sql .=" \n AND SUBSTR(ghgsh.ghg_month, 1, 10) <= '$end_date'";

				$sql .="\n GROUP BY ghgsh_tmpl_id
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
        $sqlData = DB::select(DB::raw($sql));
        $collection = collect($sqlData);
        return $collection;
    }

}
