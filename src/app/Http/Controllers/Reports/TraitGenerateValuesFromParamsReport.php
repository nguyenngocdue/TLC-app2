<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DateReport;
use App\Utils\Support\Report;

trait TraitGenerateValuesFromParamsReport
{
    public function generateValuesFromParamsReport($params){
        $valOfParams = DateReport::createValueForParams([
            'sub_project_id',
            'project_id',
            'prod_routing_id',
            'prod_order_id',
            'prod_routing_link_id',
            'erp_routing_link_id',
            'prod_discipline_id',
            'picker_date',
            'status',
            'report_type',
            'user_team_ncr',
            'optionPrintLayout',
            'root_cause',
            "ghg_tmpl",
            "month",
            'only_month',
            'year',
            'metric_type1',
            'metric_type2',
			'kanban_task_bucket_id',
			'kanban_task_cluster_id',
			'kanban_task_page_id',
			'kanban_task_cluster_id',
			'kanban_task_bucket_id',
			'kanban_task_id',
			'kanban_task_group_id',

        ], $params);
        return $valOfParams;
    }

    public function createValuesForDateParam($params){
		// GET MONTHS TO SHOW ON TABLE
		$months = range(1, 12);
		if(Report::checkValueOfField($params, 'half_year')){
			$months = $params['half_year']  === 'start_half_year' 
						? range(1, 6): range(7,12);
		}
		if(Report::checkValueOfField($params, 'quarter_time') && is_array($params['quarter_time'])){
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
		if(!is_array($year)) {
			$start_date = $year.'-01-01';
			$end_date = $year.'-12-31';
			if(isset($params['half_year']) && $params['year']) {
				$key = $params['half_year'];
				$strDate = DateReport::getHalfYearPeriods($params['year'])[$key];
				[$start_date, $end_date] = explode('/',$strDate);
			}
			return [$start_date, $end_date, $year, $strSqlMonth, $strSumValue];
		};
		return [];
		// Set half year
	}
}
