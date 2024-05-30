<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Ghg_sheet_080_dataSource extends Controller
{
	public function getYears($params)
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
								THEN ghgsh.total ELSE 0 END),2) AS 'scope_1_by_year_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y') = {$year} AND ghgsh.ghg_tmpl_id IN (5) 
								THEN ghgsh.total ELSE 0 END),2) AS 'scope_2_by_year_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y') = {$year} AND ghgsh.ghg_tmpl_id IN (6,7,8,9,10,15,11,16,12,13,14,23) 
								THEN ghgsh.total ELSE 0 END),2) AS 'scope_3_by_year_{$year}',
								
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') < '$year-07-01' 
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '$year-01-01' 
								AND ghgsh.ghg_tmpl_id IN (1,2,3,4) 
								THEN ghgsh.total ELSE 0 END),2) AS 'sum_first_range_scope_1_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '$year-07-01'
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') <= '$year-12-31' 
								AND ghgsh.ghg_tmpl_id IN (1,2,3,4) 
								THEN ghgsh.total ELSE 0 END),2) AS 'sum_second_range_scope_1_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') < '$year-07-01' 
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '$year-01-01' 
								AND ghgsh.ghg_tmpl_id IN (5) 
								THEN ghgsh.total ELSE 0 END),2) AS 'sum_first_range_scope_2_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '$year-07-01' 
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') <= '$year-12-31' 
								AND ghgsh.ghg_tmpl_id IN (5) 
								THEN ghgsh.total ELSE 0 END),2) AS 'sum_second_range_scope_2_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') < '$year-07-01' 
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '$year-01-01' 
								AND ghgsh.ghg_tmpl_id IN (6,7,8,9,10,15,11,16,12,13,14,23) 
								THEN ghgsh.total ELSE 0 END),2) AS 'sum_first_range_scope_3_{$year}',
						ROUND(SUM(CASE WHEN  
								DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') >= '$year-07-01' 
								AND DATE_FORMAT(ghgsh.ghg_month, '%Y-%m-%d') <= '$year-12-31' 
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
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') < '$year-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '$year-01-01' 
					THEN 1 ELSE 0 END) AS 'occ_accidents_sum_first_range_{$year}',
				SUM(CASE WHEN hseinr.incident_doc_type_id = 108 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '$year-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') <= '$year-12-31' 
					THEN 1 ELSE 0 END) AS 'occ_accidents_sum_second_range_{$year}',

				SUM(CASE WHEN hseinr.incident_doc_type_id = 109 
					AND DATE_FORMAT(hseinr.issue_datetime,'%Y') = {$year} 
					THEN 1 ELSE 0 END) AS occ_near_miss_by_year_{$year},
				SUM(CASE WHEN hseinr.incident_doc_type_id = 109 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') < '$year-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '$year-01-01' 
					THEN 1 ELSE 0 END) AS 'occ_near_miss_sum_first_range_{$year}',
				SUM(CASE WHEN hseinr.incident_doc_type_id = 109 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '$year-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') <= '$year-12-31' 
					THEN 1 ELSE 0 END) AS 'occ_near_miss_sum_second_range_{$year}',
					
				SUM(CASE WHEN hseinr.incident_doc_type_id IN (107,108,109)
					AND DATE_FORMAT(hseinr.issue_datetime,'%Y') = {$year} 
					THEN hseinr.lost_days ELSE 0 END)*8  AS total_lost_time_by_year_{$year},
				SUM(CASE WHEN hseinr.incident_doc_type_id IN (107,108,109)
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') < '$year-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '$year-01-01' 
					THEN hseinr.lost_days ELSE 0 END)*8  AS 'total_lost_time_sum_first_range_{$year}',
				SUM(CASE WHEN hseinr.incident_doc_type_id IN (107,108,109) 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') >= '$year-07-01' 
					AND DATE_FORMAT(hseinr.issue_datetime, '%Y-%m-%d') <= '$year-12-31' 
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

	private function getDataHumanCapitalDirectEmpSource($params)
	{
		$years = $this->getYears($params);
		$strSQL1 = "SELECT \n";

		$strSQL2 = "\n FROM (SELECT \n";
		foreach ($years as $year) {
			$strSQL1 .= "	SUM(tb.c1_total_direct_emp_by_year_{$year} + tb.c2_total_direct_emp_by_year_{$year}) AS total_direct_emp_by_year_{$year},
							SUM(tb.c1_first_range_total_direct_emp_by_half_year_{$year} + tb.c2_first_range_total_direct_emp_by_half_year_{$year}) AS first_range_total_direct_emp_by_half_year_{$year},
							SUM(tb.c1_second_range_total_direct_emp_by_half_year_{$year} + tb.c2_second_range_total_direct_emp_by_half_year_{$year}) AS second_range_total_direct_emp_by_half_year_{$year},
							
							#Senior employees
							SUM(tb.c1_total_senior_emp_by_year_{$year} + tb.c2_total_senior_emp_by_year_{$year}) AS senior_emp_by_year_{$year},
							SUM(tb.c1_first_range_total_senior_emp_by_half_year_{$year} + tb.c2_first_range_total_senior_emp_by_half_year_{$year}) AS first_range_total_senior_emp_by_half_year_{$year},
							SUM(tb.c1_second_range_total_senior_emp_by_half_year_{$year} + tb.c2_second_range_total_senior_emp_by_half_year_{$year}) AS second_range_total_senior_emp_by_half_year_{$year},

							#Senior employees - female
							SUM(tb.c1_total_senior_female_by_year_{$year} + tb.c2_total_senior_female_by_year_{$year}) AS senior_female_by_year_{$year},
							SUM(tb.c1_first_range_total_senior_female_by_half_year_{$year} + tb.c2_first_range_total_senior_female_by_half_year_{$year}) AS first_range_total_senior_female_by_half_year_{$year},
							SUM(tb.c1_second_range_total_senior_female_by_half_year_{$year} + tb.c2_second_range_total_senior_female_by_half_year_{$year}) AS second_range_total_senior_female_by_half_year_{$year},

							#Senior employees - male
							SUM(tb.c1_total_senior_male_by_year_{$year} + tb.c2_total_senior_male_by_year_{$year}) AS senior_male_by_year_{$year},
							SUM(tb.c1_first_range_total_senior_male_by_half_year_{$year} + tb.c2_first_range_total_senior_male_by_half_year_{$year}) AS first_range_total_senior_male_by_half_year_{$year},
							SUM(tb.c1_second_range_total_senior_male_by_half_year_{$year} + tb.c2_second_range_total_senior_male_by_half_year_{$year}) AS second_range_total_senior_male_by_half_year_{$year},

							#Junior employees
							SUM(tb.c1_total_junior_emp_by_year_{$year} + tb.c2_total_junior_emp_by_year_{$year}) AS junior_emp_by_year_{$year},
							SUM(tb.c1_first_range_total_junior_emp_by_half_year_{$year} + tb.c2_first_range_total_junior_emp_by_half_year_{$year}) AS first_range_total_junior_emp_by_half_year_{$year},
							SUM(tb.c1_second_range_total_junior_emp_by_half_year_{$year} + tb.c2_second_range_total_junior_emp_by_half_year_{$year}) AS second_range_total_junior_emp_by_half_year_{$year},
							
							#Junior employees - female
							SUM(tb.c1_total_junior_female_by_year_{$year} + tb.c2_total_junior_female_by_year_{$year}) AS junior_female_by_year_{$year},
							SUM(tb.c1_first_range_total_junior_female_by_half_year_{$year} + tb.c2_first_range_total_junior_female_by_half_year_{$year}) AS first_range_total_junior_female_by_half_year_{$year},
							SUM(tb.c1_second_range_total_junior_female_by_half_year_{$year} + tb.c2_second_range_total_junior_female_by_half_year_{$year}) AS second_range_total_junior_female_by_half_year_{$year},
							
							#Junior employees - male
							SUM(tb.c1_total_junior_male_by_year_{$year} + tb.c2_total_junior_male_by_year_{$year}) AS junior_male_by_year_{$year},
							SUM(tb.c1_first_range_total_junior_male_by_half_year_{$year} + tb.c2_first_range_total_junior_male_by_half_year_{$year}) AS first_range_total_junior_male_by_half_year_{$year},
							SUM(tb.c1_second_range_total_junior_male_by_half_year_{$year} + tb.c2_second_range_total_junior_male_by_half_year_{$year}) AS second_range_total_junior_male_by_half_year_{$year},";

			$strSQL2 .= "			
								#for the full year 
									#total direct employees 
									CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
											AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
											AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
											THEN 1 ELSE 0 END AS c1_total_direct_emp_by_year_{$year},
									CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
											AND us.last_date IS NULL
											THEN 1 ELSE 0 END AS c2_total_direct_emp_by_year_{$year},

									# Senior employees
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
												AND us.seniority_level IN (1, 2, 6)
												AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
												AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12'
												THEN 1 ELSE 0 END AS c1_total_senior_emp_by_year_{$year},
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
												AND us.last_date IS NULL
												AND us.seniority_level IN (1, 2, 6)
												THEN 1 ELSE 0 END AS c2_total_senior_emp_by_year_{$year},

											# Senior employee - female
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND us.seniority_level IN (1, 2, 6)
													AND us.gender = 0
													AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
													AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12'
													THEN 1 ELSE 0 END AS c1_total_senior_female_by_year_{$year},
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND us.last_date IS NULL
													AND us.gender = 0
													AND us.seniority_level IN (1, 2, 6)
													THEN 1 ELSE 0 END AS c2_total_senior_female_by_year_{$year},

											# Senior employee - male
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND us.seniority_level IN (1, 2, 6)
													AND us.gender = 1
													AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
													AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12'
													THEN 1 ELSE 0 END AS c1_total_senior_male_by_year_{$year},
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND us.last_date IS NULL
													AND us.gender = 1
													AND us.seniority_level IN (1, 2, 6)
													THEN 1 ELSE 0 END AS c2_total_senior_male_by_year_{$year},

									#Junior employees
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
												AND us.seniority_level IN (3, 4, 5)
												AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
												AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12'
												THEN 1 ELSE 0 END AS c1_total_junior_emp_by_year_{$year},
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
												AND us.last_date IS NULL
												AND us.seniority_level IN (3, 4, 5)
												THEN 1 ELSE 0 END AS c2_total_junior_emp_by_year_{$year},

											# Junior employee - female
												CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
														AND us.seniority_level IN (3, 4, 5)
														AND us.gender = 0
														AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
														AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12'
														THEN 1 ELSE 0 END AS c1_total_junior_female_by_year_{$year},
												CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
														AND us.last_date IS NULL
														AND us.gender = 0
														AND us.seniority_level IN (3, 4, 5)
														THEN 1 ELSE 0 END AS c2_total_junior_female_by_year_{$year},
											# Junior employee - male
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND us.seniority_level IN (3, 4, 5)
													AND us.gender = 1
													AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
													AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12'
													THEN 1 ELSE 0 END AS c1_total_junior_male_by_year_{$year},
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND us.last_date IS NULL
													AND us.gender = 1
													AND us.seniority_level IN (3, 4, 5)
													THEN 1 ELSE 0 END AS c2_total_junior_male_by_year_{$year},
					
								#for the first half of year
									#total direct employees
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
												AND DATE_FORMAT(us.last_date, '%Y-%m-%d') < '$year-07-01'
												AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-06' 
												THEN 1 ELSE 0 END AS c1_first_range_total_direct_emp_by_half_year_{$year},
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
												AND us.last_date IS NULL
												THEN 1 ELSE 0 END AS c2_first_range_total_direct_emp_by_half_year_{$year},
									# Senior employees
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
												AND us.seniority_level IN (1, 2, 6)
												AND DATE_FORMAT(us.last_date, '%Y-%m-%d') < '$year-07-01'
												AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-06' 
												THEN 1 ELSE 0 END AS c1_first_range_total_senior_emp_by_half_year_{$year},
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
												AND us.last_date IS NULL
												AND us.seniority_level IN (1, 2, 6)
												THEN 1 ELSE 0 END AS c2_first_range_total_senior_emp_by_half_year_{$year},
									
										# Senior employees - female
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
													AND us.seniority_level IN (1, 2, 6)
													AND DATE_FORMAT(us.last_date, '%Y-%m-%d') < '$year-07-01'
													AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-06' 
													AND us.gender = 0
													THEN 1 ELSE 0 END AS c1_first_range_total_senior_female_by_half_year_{$year},
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
													AND us.last_date IS NULL
													AND us.seniority_level IN (1, 2, 6)
													AND us.gender = 0
													THEN 1 ELSE 0 END AS c2_first_range_total_senior_female_by_half_year_{$year},
										
										# Senior employees - male
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
													AND us.seniority_level IN (1, 2, 6)
													AND DATE_FORMAT(us.last_date, '%Y-%m-%d') < '$year-07-01'
													AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-06' 
													AND us.gender = 1
													THEN 1 ELSE 0 END AS c1_first_range_total_senior_male_by_half_year_{$year},
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
													AND us.last_date IS NULL
													AND us.seniority_level IN (1, 2, 6)
													AND us.gender = 1
													THEN 1 ELSE 0 END AS c2_first_range_total_senior_male_by_half_year_{$year},

									# Junior employees
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
												AND us.seniority_level IN (3, 4, 5)
												AND DATE_FORMAT(us.last_date, '%Y-%m-%d') < '$year-07-01'
												AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-06' 
												THEN 1 ELSE 0 END AS c1_first_range_total_junior_emp_by_half_year_{$year},
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
												AND us.last_date IS NULL
												AND us.seniority_level IN (3, 4, 5)
												THEN 1 ELSE 0 END AS c2_first_range_total_junior_emp_by_half_year_{$year},
										# Junior employees - female
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
													AND us.seniority_level IN (3, 4, 5)
													AND DATE_FORMAT(us.last_date, '%Y-%m-%d') < '$year-07-01'
													AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-06' 
													AND us.gender = 0
													THEN 1 ELSE 0 END AS c1_first_range_total_junior_female_by_half_year_{$year},
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
													AND us.last_date IS NULL
													AND us.seniority_level IN (3, 4, 5)
													AND us.gender = 0
													THEN 1 ELSE 0 END AS c2_first_range_total_junior_female_by_half_year_{$year},
										# Junior employees - male
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
													AND us.seniority_level IN (3, 4, 5)
													AND DATE_FORMAT(us.last_date, '%Y-%m-%d') < '$year-07-01'
													AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-06' 
													AND us.gender = 1
													THEN 1 ELSE 0 END AS c1_first_range_total_junior_male_by_half_year_{$year},
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') < '$year-07-01' 
													AND us.last_date IS NULL
													AND us.seniority_level IN (3, 4, 5)
													AND us.gender = 1
													THEN 1 ELSE 0 END AS c2_first_range_total_junior_male_by_half_year_{$year},

								# for the second half of year
									#total direct employees
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
												AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
												AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
												THEN 1 ELSE 0 END AS c1_second_range_total_direct_emp_by_half_year_{$year},
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
												AND us.last_date IS NULL
												THEN 1 ELSE 0 END AS c2_second_range_total_direct_emp_by_half_year_{$year},
									# Senior employees
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
												AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
												AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
												AND us.seniority_level IN (1, 2, 6)
												THEN 1 ELSE 0 END AS c1_second_range_total_senior_emp_by_half_year_{$year},
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
												AND us.last_date IS NULL
												AND us.seniority_level IN (1, 2, 6)
												THEN 1 ELSE 0 END AS c2_second_range_total_senior_emp_by_half_year_{$year},

										# Senior employees - female
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
													AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
													AND us.seniority_level IN (1, 2, 6)
													AND us.gender = 0
													THEN 1 ELSE 0 END AS c1_second_range_total_senior_female_by_half_year_{$year},
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND us.last_date IS NULL
													AND us.seniority_level IN (1, 2, 6)
													AND us.gender = 0
													THEN 1 ELSE 0 END AS c2_second_range_total_senior_female_by_half_year_{$year},

										# Senior employees - male
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
													AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
													AND us.seniority_level IN (1, 2, 6)
													AND us.gender = 1
													THEN 1 ELSE 0 END AS c1_second_range_total_senior_male_by_half_year_{$year},
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND us.last_date IS NULL
													AND us.seniority_level IN (1, 2, 6)
													AND us.gender = 1
													THEN 1 ELSE 0 END AS c2_second_range_total_senior_male_by_half_year_{$year},
													
									# Junior employees
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
												AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
												AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
												AND us.seniority_level IN (3, 4, 5)
												THEN 1 ELSE 0 END AS c1_second_range_total_junior_emp_by_half_year_{$year},
										CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
												AND us.last_date IS NULL
												AND us.seniority_level IN (3, 4, 5)
												THEN 1 ELSE 0 END AS c2_second_range_total_junior_emp_by_half_year_{$year},

										# Junior employees - female
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
													AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
													AND us.seniority_level IN (3, 4, 5)
													AND us.gender = 0
													THEN 1 ELSE 0 END AS c1_second_range_total_junior_female_by_half_year_{$year},
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND us.last_date IS NULL
													AND us.seniority_level IN (3, 4, 5)
													AND us.gender = 0
													THEN 1 ELSE 0 END AS c2_second_range_total_junior_female_by_half_year_{$year},
										# Junior employees - male
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
													AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
													AND us.seniority_level IN (3, 4, 5)
													AND us.gender = 1
													THEN 1 ELSE 0 END AS c1_second_range_total_junior_male_by_half_year_{$year},
											CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
													AND us.last_date IS NULL
													AND us.seniority_level IN (3, 4, 5)
													AND us.gender = 1
													THEN 1 ELSE 0 END AS c2_second_range_total_junior_male_by_half_year_{$year},";
		}

		$strSQL1 = trim($strSQL1, ",");
		$strSQL2 = trim($strSQL2, ",");

		// $strSQL = trim($strSQL, ",");
		$strSQL2 .= "\n FROM users us
		WHERE 1 = 1
		AND us.show_on_beta = 0 ) AS tb";
		$strSQL = $strSQL1 . $strSQL2;
		$dataSQL = collect(DB::select($strSQL));
		return $dataSQL;
	}

	public function divideTotalDirectEmployees($params)
	{
		$dataSQL = $this->getDataHumanCapitalDirectEmpSource($params)->first();
		$result = [];
		foreach ($dataSQL as $key => $value) {
			if (str_contains($key, 'total_direct')) {
				$result['total_direct_employees'][$key] = $value;
			}
			if (str_contains($key, 'senior_emp')) {
				$result['senior_employees'][$key] = $value;
			}
			if (str_contains($key, 'senior_female')) {
				$result['senior_employees_female'][$key] = $value;
			}
			if (str_contains($key, 'senior_male')) {
				$result['senior_employees_male'][$key] = $value;
			}
			if (str_contains($key, 'junior_emp')) {
				$result['junior_employees'][$key] = $value;
			}
			if (str_contains($key, 'junior_female')) {
				$result['junior_employees_female'][$key] = $value;
			}
			if (str_contains($key, 'junior_male')) {
				$result['junior_employees_male'][$key] = $value;
			}
		}
		return $result;
	}

	private function getDataHumanCapitalNewEmployees($params)
	{
		$years = $this->getYears($params);
		$strSQL1 = "SELECT \n";

		$strSQL2 = "\n FROM (SELECT \n";

		foreach ($years as $year) {
			$yearMinusOne = $year - 1;
			$strSQL1 .= "	SUM((tb.c1_new_employees_by_{$year} + tb.c2_new_employees_by_{$year}) - (tb.c3_new_employees_by_{$year} + tb.c4_new_employees_by_{$year})) AS total_new_employees_by_year_{$year},
							SUM((tb.c1_first_range_new_employees_by_half_year_{$year} + tb.c2_first_range_new_employees_by_half_year_{$year}) - (tb.c3_first_range_new_employees_by_half_year_{$year} + tb.c4_first_range_new_employees_by_half_year_{$year})) AS first_range_total_new_employees_by_half_sum_year_{$year},
							SUM((tb.c1_second_range_new_employees_by_half_year_{$year} + tb.c2_second_range_new_employees_by_half_year_{$year}) - (tb.c3_second_range_new_employees_by_half_year_{$year} + tb.c4_second_range_new_employees_by_half_year_{$year})) AS second_range_total_new_employees_by_half_sum_year_{$year},

							SUM((tb.c1_new_employees_female_by_year_{$year} + tb.c2_new_employees_female_by_year_{$year}) - (tb.c3_new_employees_female_by_year_{$year} + tb.c4_new_employees_female_by_year_{$year})) AS new_employees_female_by_year_{$year},
							SUM((tb . c1_first_range_new_employees_female_by_half_year_{$year} + tb.c2_first_range_new_employees_female_by_half_year_{$year}) - (tb.c3_first_range_new_employees_female_by_half_year_{$year} + tb.c4_first_range_new_employees_female_by_half_year_{$year})) AS first_range_new_employees_female_by_half_sum_year_{$year},
							SUM((tb . c1_second_range_new_employees_female_by_half_year_{$year} + tb.c2_second_range_new_employees_female_by_half_year_{$year}) - (tb.c3_second_range_new_employees_female_by_half_year_{$year} + tb.c4_second_range_new_employees_female_by_half_year_{$year})) AS second_range_new_employees_female_by_half_sum_year_{$year},
							
							SUM((tb.c1_new_employees_male_by_year_{$year} + tb.c2_new_employees_male_by_year_{$year}) - (tb.c3_new_employees_male_by_year_{$year} + tb.c4_new_employees_male_by_year_{$year})) AS new_employees_male_by_year_{$year},
							SUM((tb . c1_first_range_new_employees_male_by_half_year_{$year} + tb.c2_first_range_new_employees_male_by_half_year_{$year}) - (tb.c3_first_range_new_employees_male_by_half_year_{$year} + tb.c4_first_range_new_employees_male_by_half_year_{$year})) AS first_range_new_employees_male_by_half_sum_year_{$year},
							SUM((tb . c1_second_range_new_employees_male_by_half_year_{$year} + tb.c2_second_range_new_employees_male_by_half_year_{$year}) - (tb.c3_second_range_new_employees_male_by_half_year_{$year} + tb.c4_second_range_new_employees_male_by_half_year_{$year})) AS second_range_new_employees_male_by_half_sum_year_{$year},";
			$strSQL2 .= " 
							#New Employees for the full year 
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
										THEN 1 ELSE 0 END AS c1_new_employees_by_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
										AND us.last_date IS NULL
										THEN 1 ELSE 0 END AS c2_new_employees_by_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-12-31' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '{$yearMinusOne}-12-31'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '{$yearMinusOne}-12' 
										THEN 1 ELSE 0 END AS c3_new_employees_by_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-12-31' 
										AND us.last_date IS NULL
										THEN 1 ELSE 0 END AS c4_new_employees_by_{$year},
									
							
							#New Employees for the first half year 
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-06-30' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-06-30'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-06' 
										THEN 1 ELSE 0 END AS c1_first_range_new_employees_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-06-30' 
										AND us.last_date IS NULL
										THEN 1 ELSE 0 END AS c2_first_range_new_employees_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$yearMinusOne-06-30' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '{$yearMinusOne}-06' 
										THEN 1 ELSE 0 END AS c3_first_range_new_employees_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30' 
										AND us.last_date IS NULL
										THEN 1 ELSE 0 END AS c4_first_range_new_employees_by_half_year_{$year},
							
							#New Employees for the second half year 
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') > '$year-06-30' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') > '$year-06-30'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
										THEN 1 ELSE 0 END AS c1_second_range_new_employees_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') > '$year-06-30' 
										AND us.last_date IS NULL
										THEN 1 ELSE 0 END AS c2_second_range_new_employees_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '{$yearMinusOne}-06' 
										THEN 1 ELSE 0 END AS c3_second_range_new_employees_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30' 
										AND us.last_date IS NULL
										THEN 1 ELSE 0 END AS c4_second_range_new_employees_by_half_year_{$year},
										
							#New Employees female for the full year 
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
										AND us.gender = 0
										THEN 1 ELSE 0 END AS c1_new_employees_female_by_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
										AND us.last_date IS NULL
										AND us.gender = 0
										THEN 1 ELSE 0 END AS c2_new_employees_female_by_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-12-31' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '{$yearMinusOne}-12-31'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '{$yearMinusOne}-12' 
										AND us.gender = 0
										THEN 1 ELSE 0 END AS c3_new_employees_female_by_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-12-31' 
										AND us.last_date IS NULL
										AND us.gender = 0
										THEN 1 ELSE 0 END AS c4_new_employees_female_by_year_{$year},

							#New Employees Female for the first half year 
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-06-30' 
											AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-06-30'
											AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-06'
											AND us.gender = 0
											THEN 1 ELSE 0 END AS c1_first_range_new_employees_female_by_half_year_{$year},
									CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-06-30' 
											AND us.last_date IS NULL
											AND us.gender = 0
											THEN 1 ELSE 0 END AS c2_first_range_new_employees_female_by_half_year_{$year},
									CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30' 
											AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30'
											AND DATE_FORMAT(us.last_date, '%Y-%m') = '{$yearMinusOne}-06' 
											AND us.gender = 0
											THEN 1 ELSE 0 END AS c3_first_range_new_employees_female_by_half_year_{$year},
									CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30' 
											AND us.last_date IS NULL
											AND us.gender = 0
											THEN 1 ELSE 0 END AS c4_first_range_new_employees_female_by_half_year_{$year},
								
							#New Employees - Female for the second half year 
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') > '$year-06-30' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') > '$year-06-30'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
										AND us.gender = 0
										THEN 1 ELSE 0 END AS c1_second_range_new_employees_female_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') > '$year-06-30' 
										AND us.last_date IS NULL
										AND us.gender = 0
										THEN 1 ELSE 0 END AS c2_second_range_new_employees_female_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '{$yearMinusOne}-06' 
										AND us.gender = 0
										THEN 1 ELSE 0 END AS c3_second_range_new_employees_female_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30' 
										AND us.last_date IS NULL
										AND us.gender = 0
										THEN 1 ELSE 0 END AS c4_second_range_new_employees_female_by_half_year_{$year},


							#New Employees Male for the full year 
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
										AND us.gender = 1
										THEN 1 ELSE 0 END AS c1_new_employees_male_by_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-12-31' 
										AND us.last_date IS NULL
										AND us.gender = 1
										THEN 1 ELSE 0 END AS c2_new_employees_male_by_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-12-31' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '{$yearMinusOne}-12-31'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '{$yearMinusOne}-12' 
										AND us.gender = 1
										THEN 1 ELSE 0 END AS c3_new_employees_male_by_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-12-31' 
										AND us.last_date IS NULL
										AND us.gender = 1
										THEN 1 ELSE 0 END AS c4_new_employees_male_by_year_{$year},
										
							#New Employees Male for the first half year 
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-06-30' 
											AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-06-30'
											AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-06'
											AND us.gender = 1
											THEN 1 ELSE 0 END AS c1_first_range_new_employees_male_by_half_year_{$year},
									CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '$year-06-30' 
											AND us.last_date IS NULL
											AND us.gender = 1
											THEN 1 ELSE 0 END AS c2_first_range_new_employees_male_by_half_year_{$year},
									CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30' 
											AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30'
											AND DATE_FORMAT(us.last_date, '%Y-%m') = '{$yearMinusOne}-06' 
											AND us.gender = 1
											THEN 1 ELSE 0 END AS c3_first_range_new_employees_male_by_half_year_{$year},
									CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30' 
											AND us.last_date IS NULL
											AND us.gender = 1
											THEN 1 ELSE 0 END AS c4_first_range_new_employees_male_by_half_year_{$year},
								
							#New Employees Male for the second half year 
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') > '$year-06-30' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') > '$year-06-30'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '$year-12' 
										AND us.gender = 1
										THEN 1 ELSE 0 END AS c1_second_range_new_employees_male_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') > '$year-06-30' 
										AND us.last_date IS NULL
										AND us.gender = 1
										THEN 1 ELSE 0 END AS c2_second_range_new_employees_male_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30' 
										AND DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30'
										AND DATE_FORMAT(us.last_date, '%Y-%m') = '{$yearMinusOne}-06' 
										AND us.gender = 1
										THEN 1 ELSE 0 END AS c3_second_range_new_employees_male_by_half_year_{$year},
								CASE WHEN DATE_FORMAT(us.first_date, '%Y-%m-%d') <= '{$yearMinusOne}-06-30' 
										AND us.last_date IS NULL
										AND us.gender = 1
										THEN 1 ELSE 0 END AS c4_second_range_new_employees_male_by_half_year_{$year},";
		};
		$strSQL1 = trim($strSQL1, ",");
		$strSQL2 = trim($strSQL2, ",");
		$strSQL2 .= "\n FROM users us
									WHERE 1 = 1
									AND us.show_on_beta = 0 )tb";
		$strSQL = $strSQL1 . $strSQL2;

		// dd($strSQL);
		$dataSQL = collect(DB::select($strSQL));
		return $dataSQL;
	}

	public function divideNewEmployees($params)
	{
		$dataSQL = $this->getDataHumanCapitalNewEmployees($params)->first();
		$result = [];
		foreach ($dataSQL as $key => $value) {
			if (str_contains($key, 'total_new_employees')) {
				$result['total_new_employees'][$key] = $value;
			}
			if (str_contains($key, 'new_employees_female')) {
				$result['new_employees_female'][$key] = $value;
			}
			if (str_contains($key, 'new_employees_male')) {
				$result['new_employees_male'][$key] = $value;
			}
		}
		// dd($result);
		return $result;
	}

	private function getDataDepartedEmployees($params)
	{
		$years = $this->getYears($params);
		$strSQL1 = "SELECT \n";
		$strSQL2 = "";
		foreach ($years as $year) {
			$strSQL2 .= "
						#Calculate departed employees for the full year
							SUM(CASE WHEN DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
								AND DATE_FORMAT(us.last_date, '%Y') = '{$year}'
								THEN 1 ELSE 0 END) AS total_departed_employees_by_year_{$year},

						#Departed employees for the first half year 
							SUM(CASE WHEN DATE_FORMAT(us.last_date, '%Y-%m-%d') < '$year-07-01'
								AND DATE_FORMAT(us.last_date, '%Y') = '{$year}'
								THEN 1 ELSE 0 END) AS first_range_total_departed_employees_by_half_year_{$year},
						
						#Departed employees for the second half year 
							SUM(CASE WHEN DATE_FORMAT(us.last_date, '%Y-%m-%d') >= '$year-07-01'
								AND DATE_FORMAT(us.last_date, '%Y') = '{$year}'
								THEN 1 ELSE 0 END) AS second_range_total_departed_employees_by_half_year_{$year},

						#Departed employees - female for the full year 
							SUM(CASE WHEN DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
								AND DATE_FORMAT(us.last_date, '%Y') = '{$year}'
								AND us.gender = 0
								THEN 1 ELSE 0 END) AS departed_employees_female_by_year_{$year},
						#Departed employees -female for the first half year 
							SUM(CASE WHEN DATE_FORMAT(us.last_date, '%Y-%m-%d') < '$year-07-01'
								AND DATE_FORMAT(us.last_date, '%Y') = '{$year}'
								AND us.gender = 0
								THEN 1 ELSE 0 END) AS first_range_departed_employees_female_by_half_year_{$year},
						#Departed employees - female for the second half year 
							SUM(CASE WHEN DATE_FORMAT(us.last_date, '%Y-%m-%d') >= '$year-07-01'
								AND DATE_FORMAT(us.last_date, '%Y') = '{$year}'
								AND us.gender = 0
								THEN 1 ELSE 0 END) AS second_range_departed_employees_female_by_half_year_{$year},

						#Departed employees - male for the full year 
							SUM(CASE WHEN DATE_FORMAT(us.last_date, '%Y-%m-%d') <= '$year-12-31'
								AND DATE_FORMAT(us.last_date, '%Y') = '{$year}'
								AND us.gender = 1
								THEN 1 ELSE 0 END) AS departed_employees_male_by_year_{$year},
						#Departed employees - male for the first half year 
							SUM(CASE WHEN DATE_FORMAT(us.last_date, '%Y-%m-%d') < '$year-07-01'
								AND DATE_FORMAT(us.last_date, '%Y') = '{$year}'
								AND us.gender = 1
								THEN 1 ELSE 0 END) AS first_range_departed_employees_male_by_half_year_{$year},
						#Departed employees - female for the second half year 
							SUM(CASE WHEN DATE_FORMAT(us.last_date, '%Y-%m-%d') >= '$year-07-01'
								AND DATE_FORMAT(us.last_date, '%Y') = '{$year}'
								AND us.gender = 1
								THEN 1 ELSE 0 END) AS second_range_departed_employees_male_by_half_year_{$year},";
		}
		$strSQL = $strSQL1 . $strSQL2;
		$strSQL = trim($strSQL, ",");
		$strSQL .= "\n FROM users us
								WHERE 1 = 1
								AND us.show_on_beta = 0 AND us.last_date IS NOT NULL";
		$dataSQL = collect(DB::select($strSQL));
		return $dataSQL;
	}

	public function divideDepartedEmployees($params)
	{
		$dataSQL = $this->getDataDepartedEmployees($params)->first();
		$result = [];
		foreach ($dataSQL as $key => $value) {
			if (str_contains($key, 'total_departed_employees')) {
				$result['total_departed_employees'][$key] = $value;
			}
			if (str_contains($key, 'departed_employees_female')) {
				$result['departed_employees_female'][$key] = $value;
			}
			if (str_contains($key, 'departed_employees_male')) {
				$result['departed_employees_male'][$key] = $value;
			}
		}
		// dump($result);
		return $result;
	}
}
