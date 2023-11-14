<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Ghg_sheet_010;
use App\Http\Controllers\Reports\Reports\Ghg_sheet_dataSource;
use App\Http\Controllers\Reports\TraitConversionFieldNameGhgReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitGenerateValuesFromParamsReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\DateReport;
use App\Utils\Support\DocumentReport;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;

class Ghg_sheet_050 extends Report_ParentDocument2Controller
{

	use TraitForwardModeReport;
	use TraitParamsSettingReport;
	use TraitGenerateValuesFromParamsReport;

	protected $viewName = 'document-ghg-sheet-050';
	protected $year = '2023';

	public function getSqlStr($params)
    {
		[$start_date, $end_date, $year, $strSqlMonth, $strSumValue] = $this->createValuesForDateParam($params);
			
        $sql = "SELECT 
					$strSqlMonth
					ghgsh_totals.ghg_tmpls_id,
					ghgsh_totals.ghg_tmpls_name, 
					ghgsh_totals.ghg_metric_type_id,
					ghgsh_totals.ghg_metric_type_name,
					ghgsh_totals.ghg_metric_type_1_id,
					ghgsh_totals.ghg_metric_type_1_name,
					ghgsh_totals.ghg_metric_type_2_id,
					ghgsh_totals.ghg_metric_type_2_name
					
					FROM (SELECT
						#GROUP_CONCAT(tb1.ghg_sheet_id)  AS ghg_sheet_id,
						tb1.ghg_tmpls_id,
						tb1.ghg_tmpls_name, 
						tb1.ghg_metric_type_id,
						tb1.ghg_metric_type_name,
						tb1.ghg_metric_type_1_id,
						tb1.ghg_metric_type_1_name,
						tb1.ghg_metric_type_2_id,
						tb1.ghg_metric_type_2_name,
						SUM(tb1.`01`)AS `01`,
						SUM(tb1.`02`)AS `02`,
						SUM(tb1.`03`)AS `03`,
						SUM(tb1.`04`)AS `04`,
						SUM(tb1.`05`)AS `05`,
						SUM(tb1.`06`)AS `06`,
						SUM(tb1.`07`)AS `07`,
						SUM(tb1.`08`)AS `08`,
						SUM(tb1.`09`)AS `09`,
						SUM(tb1.`10`)AS `10`,
						SUM(tb1.`11`)AS `11`,
						SUM(tb1.`12`)AS `12`
						FROM (SELECT
							ghgs.id AS ghg_sheet_id,
							ghgs.name AS ghg_sheet_name,
							
							ghgtmpls.id AS ghg_tmpls_id,
							ghgtmpls.name AS ghg_tmpls_name,
							
							ghgmt.id AS ghg_metric_type_id,
							ghgmt.name AS ghg_metric_type_name,
							
							ghgsl.ghg_metric_type_1_id AS ghg_metric_type_1_id,
							ghgmt1.name AS ghg_metric_type_1_name,
						
							ghgsl.ghg_metric_type_2_id AS ghg_metric_type_2_id,
							ghgmt2.name AS ghg_metric_type_2_name,
							terms.name AS unit,
							#ghgsl.total AS total,
							SUBSTRING(ghgs.ghg_month, 1, 4) AS ghg_year,
							SUBSTRING(ghgs.ghg_month, 6, 2) AS ghg_month,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '01' THEN ghgsl.total ELSE 0 END)/1000 , 2) AS `01`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '02' THEN ghgsl.total ELSE 0 END)/1000 , 2) AS `02`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '03' THEN ghgsl.total ELSE 0 END)/1000 , 2) AS `03`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '04' THEN ghgsl.total ELSE 0 END)/1000, 2) AS `04`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '05' THEN ghgsl.total ELSE 0 END)/1000 , 2) AS `05`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '06' THEN ghgsl.total ELSE 0 END)/1000 , 2) AS `06`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '07' THEN ghgsl.total ELSE 0 END)/1000 , 2) AS `07`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '08' THEN ghgsl.total ELSE 0 END)/1000 , 2) AS `08`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '09' THEN ghgsl.total ELSE 0 END)/1000 , 2) AS `09`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '10' THEN ghgsl.total ELSE 0 END)/1000 , 2) AS `10`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '11' THEN ghgsl.total ELSE 0 END)/1000 , 2) AS `11`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '12' THEN ghgsl.total ELSE 0 END)/1000 , 2) AS `12`
							FROM  
								ghg_sheets ghgs
							JOIN ghg_tmpls ghgtmpls ON ghgs.ghg_tmpl_id = ghgtmpls.id
							JOIN ghg_sheet_lines ghgsl ON ghgsl.ghg_sheet_id = ghgs.id
							LEFT JOIN ghg_metric_type_1s ghgmt1 ON ghgmt1.id = ghgsl.ghg_metric_type_1_id
							JOIN ghg_metric_types ghgmt ON ghgmt1.ghg_metric_type_id = ghgmt.id
							LEFT JOIN ghg_metric_type_2s ghgmt2 ON ghgmt2.id = ghgsl.ghg_metric_type_2_id
							LEFT JOIN terms terms ON terms.id = ghgsl.unit
							WHERE 
								1 = 1
								AND SUBSTR(ghgs.ghg_month, 1, 4) = $year";
								if(isset($params['half_year']) && $start_date) $sql .=" \n AND SUBSTR(ghgs.ghg_month, 1, 10) >= '$start_date'";
								if(isset($params['half_year']) && $end_date) $sql .=" \n AND SUBSTR(ghgs.ghg_month, 1, 10) <= '$end_date'";	
															
							$sql .= "\n GROUP BY 
								ghgtmpls.id, ghgmt.name, ghgmt1.name, ghgmt2.name, ghgmt.id, 
								ghgsl.ghg_metric_type_1_id, ghgsl.ghg_metric_type_2_id, 
								terms.name, ghg_year, ghg_month#, total
							ORDER BY 
								ghg_year, ghg_month, ghg_tmpls_name) AS tb1
								GROUP BY 	ghg_tmpls_id, 
											ghg_metric_type_id, 
											ghg_metric_type_1_id, 
											ghg_metric_type_2_id) AS ghgsh_totals";
        return $sql;
    }

	public function getParamColumns($dataSource = [], $modeType = '')
	{
		return [
			[
				'title' => 'Year',
				'dataIndex' => 'year',
			],
			[
				'title' => 'Quarter',
				'dataIndex' => 'quarter_time',
				'allowClear' => true,
				// 'multiple' => true,

			],
			[
				'title' => 'Month',
				'dataIndex' => 'only_month',
				'allowClear' => true,
				'multiple' => true,
				'hasListenTo' => true,
			],
		];
	}

	private function groupValuesOfMonth($data){
		foreach ($data as $key => &$value){
			for ($i = 1; $i <= 12; $i++) {
				$monthKey = str_pad($i, 2, '0', STR_PAD_LEFT);
				if(!isset($value[$monthKey])) continue;
				$value['months'][$monthKey] = $value[$monthKey];
				unset($value[$monthKey]); 
			}
			//summary values of months
			$value['total_months'] = array_sum($value['months']);
		}	
		return $data;	
	}


	public function changeDataSource($dataSource, $params)
	{
		
		$primaryData = (new Ghg_sheet_dataSource())->getDataSource($params);
		$primaryData =  Report::convertToType($primaryData);
		$groupByGhgTmplId =  Report::groupArrayByKey($dataSource, 'ghg_tmpls_id');
		// dd($groupByGhgTmplId);

		foreach ($primaryData as $key => &$items){
			if (isset($items['ghg_tmpl_id'])){
				if(isset($groupByGhgTmplId[$items['ghg_tmpl_id']])){
					$dataIndex = $groupByGhgTmplId[$items['ghg_tmpl_id']];
					$dataIndex = self::groupValuesOfMonth($dataIndex);
					// dd($dataIndex);
					$items['children_metrics'] = $dataIndex;
				}
			}
		}
 

		// dd($primaryData);
		$dataSource = $primaryData;
		
		$dataSource =  Report::convertToType($dataSource);
		$dataSource = DocumentReport::groupMonths($dataSource);
		$months = reset($dataSource)['months'];
		$monthlyTotals = ['sum_total_months' => 0.0] + array_fill_keys(array_keys($months), 0.0);
		foreach ($dataSource as $key => &$item) {
			$monthlyTotals['sum_total_months'] += $item['total_months'];
			foreach ($item['months'] as $month => $value) {
				$monthlyTotals[(string)$month] += (int)$value;
				unset($item[$month]);
			}
			$item['month_ghg_sheet_id'] = StringReport::parseKeyValueString($item['month_ghg_sheet_id']);
		}
		$groupByScope = Report::groupArrayByKey($dataSource, 'scope_id');
		$groupByScope = ['scopes' => array_map(fn ($item) => Report::groupArrayByKey($item, 'ghgcate_id'), $groupByScope)];
		$groupByScope['total_emission'] = $monthlyTotals;
		// dd($groupByScope);

		return collect($groupByScope);
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
				if (!isset($values2['months'])) continue;
				$array['months'] = array_keys(reset($values2)['months']);
			}
			$info[$k1] = $array;
		}
		return $info;
	}
}
