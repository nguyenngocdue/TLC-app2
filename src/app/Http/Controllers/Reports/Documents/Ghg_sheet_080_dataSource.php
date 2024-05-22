<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Ghg_sheet_080_dataSource extends Controller
{
	private function getYears($params)
	{
		return is_array($params['year']) ? $params['year'] : [$params['year']];
	}
	private function getDataSourceBy3Scopes($params)
	{
		$years = $this->getYears($params);
		$strSQL = "SELECT \n";
		foreach ($years as $year) {
			$strSQL .= "ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y') = {$year} AND ghgsh.ghg_tmpl_id IN (1,2,3,4) 
								THEN ghgsh.total ELSE 0 END),2) AS 'scope_1_by_sum_year_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y') = {$year} AND ghgsh.ghg_tmpl_id IN (5) 
								THEN ghgsh.total ELSE 0 END),2) AS 'scope_2_by_sum_year_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y') = {$year} AND ghgsh.ghg_tmpl_id IN (6,7,8,9,10,15,11,16,12,13,14,23) 
								THEN ghgsh.total ELSE 0 END),2) AS 'scope_3_by_sum_year_{$year}',
								
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') < '{$year}-07-01' 
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '{$year}-01-01' 
								AND ghgsh.ghg_tmpl_id IN (1,2,3,4) 
								THEN ghgsh.total ELSE 0 END),2) AS 'sum_first_range_scope_1_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '{$year}-07-01'
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') <= '{$year}-12-31' 
								AND ghgsh.ghg_tmpl_id IN (1,2,3,4) 
								THEN ghgsh.total ELSE 0 END),2) AS 'sum_second_range_scope_1_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') < '{$year}-07-01' 
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '{$year}-01-01' 
								AND ghgsh.ghg_tmpl_id IN (5) 
								THEN ghgsh.total ELSE 0 END),2) AS 'sum_first_range_scope_2_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '{$year}-07-01' 
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') <= '{$year}-12-31' 
								AND ghgsh.ghg_tmpl_id IN (5) 
								THEN ghgsh.total ELSE 0 END),2) AS 'sum_second_range_scope_2_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') < '{$year}-07-01' 
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '{$year}-01-01' 
								AND ghgsh.ghg_tmpl_id IN (6,7,8,9,10,15,11,16,12,13,14,23) 
								THEN ghgsh.total ELSE 0 END),2) AS 'sum_first_range_scope_3_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '{$year}-07-01' 
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') <= '{$year}-12-31' 
								AND ghgsh.ghg_tmpl_id IN (6,7,8,9,10,15,11,16,12,13,14,23) 
								THEN ghgsh.total ELSE 0 END),2) AS 'sum_second_range_scope_3_{$year}',";
		}
		$strSQL = trim($strSQL, ",");
		$strSQL .= "\n FROM ghg_sheets ghgsh";
		$dataSQL = DB::select($strSQL);
		return collect($dataSQL);
	}

	public function divide3Scopes($params)
	{
		$dataSQL = $this->getDataSourceBy3Scopes($params)->first();
		$result = [];
		foreach ($dataSQL as $key => $value) {
			if (str_contains($key, 'scope_1')) {
				$result['scope_1_ghg_emissions'][$key] = $value;
			}
			if (str_contains($key, 'scope_2')) {
				$result['scope_2_ghg_emissions'][$key] = $value;
			}
			if (str_contains($key, 'scope_3')) {
				$result['scope_3_ghg_emissions'][$key] = $value;
			}
		}
		return $result;
	}

	private function getDataSourceOccupationalAccident($params)
	{
		$years = $this->getYears($params);
		$strSQL = "SELECT \n";
		foreach ($years as $year) {
			$strSQL .= "
				SUM(CASE WHEN hseinr.incident_doc_type_id = 108 
					AND DATE_FORMAT(hseinr.issue_datetime,'%Y') = {$year} 
					THEN 1 ELSE 0 END) AS occ_accidents_by_year_{$year},
				SUM(CASE WHEN hseinr.incident_doc_type_id = 108 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') < '{$year}-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '{$year}-01-01' 
					THEN 1 ELSE 0 END) AS 'occ_accidents_sum_first_range_{$year}',
				SUM(CASE WHEN hseinr.incident_doc_type_id = 108 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '{$year}-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') <= '{$year}-12-31' 
					THEN 1 ELSE 0 END) AS 'occ_accidents_sum_second_range_{$year}',

				SUM(CASE WHEN hseinr.incident_doc_type_id = 109 
					AND DATE_FORMAT(hseinr.issue_datetime,'%Y') = {$year} 
					THEN 1 ELSE 0 END) AS occ_near_miss_by_year_{$year},
				SUM(CASE WHEN hseinr.incident_doc_type_id = 109 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') < '{$year}-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '{$year}-01-01' 
					THEN 1 ELSE 0 END) AS 'occ_near_miss_sum_first_range_{$year}',
				SUM(CASE WHEN hseinr.incident_doc_type_id = 109 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '{$year}-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') <= '{$year}-12-31' 
					THEN 1 ELSE 0 END) AS 'occ_near_miss_sum_second_range_{$year}',
					
				SUM(CASE WHEN hseinr.incident_doc_type_id IN (107,108,109)
					AND DATE_FORMAT(hseinr.issue_datetime,'%Y') = {$year} 
					THEN hseinr.lost_days ELSE 0 END)*8  AS total_lost_time_by_year_{$year},
				SUM(CASE WHEN hseinr.incident_doc_type_id IN (107,108,109)
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') < '{$year}-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '{$year}-01-01' 
					THEN hseinr.lost_days ELSE 0 END)*8  AS 'total_lost_time_sum_first_range_{$year}',
				SUM(CASE WHEN hseinr.incident_doc_type_id IN (107,108,109) 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '{$year}-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') <= '{$year}-12-31' 
					THEN hseinr.lost_days ELSE 0 END)*8  AS 'total_lost_time_sum_second_range_{$year}',";
		}
		$strSQL = trim($strSQL, ",");
		$strSQL .= "\n FROM hse_incident_reports hseinr";
		$dataSQL = DB::select($strSQL);
		return collect($dataSQL);
	}

	public function divide3Accidents($params)
	{
		$dataSQL = $this->getDataSourceOccupationalAccident($params)->first();
		$result = [];
		foreach ($dataSQL as $key => $value) {
			if (str_contains($key, 'occ_accidents')) {
				$result['occupational_accidents'][$key] = $value;
			}
			if (str_contains($key, 'occ_near_miss')) {
				$result['occupational_near_miss'][$key] = $value;
			}
			if (str_contains($key, 'total_lost_time')) {
				$result['total_lost_time'][$key] = $value;
			}
		}
		return $result;
	}
}
