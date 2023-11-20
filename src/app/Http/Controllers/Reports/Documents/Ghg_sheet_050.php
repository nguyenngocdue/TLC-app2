<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Ghg_sheet_010;
use App\Http\Controllers\Reports\Reports\Ghg_sheet_dataSource;
use App\Http\Controllers\Reports\TraitConversionFieldNameGhgReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitGenerateValuesFromParamsReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\ArrayReport;
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
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '01' THEN ghgsl.total ELSE 0 END)/1000 , 3) AS `01`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '02' THEN ghgsl.total ELSE 0 END)/1000 , 3) AS `02`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '03' THEN ghgsl.total ELSE 0 END)/1000 , 3) AS `03`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '04' THEN ghgsl.total ELSE 0 END)/1000, 3) AS `04`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '05' THEN ghgsl.total ELSE 0 END)/1000 , 3) AS `05`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '06' THEN ghgsl.total ELSE 0 END)/1000 , 3) AS `06`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '07' THEN ghgsl.total ELSE 0 END)/1000 , 3) AS `07`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '08' THEN ghgsl.total ELSE 0 END)/1000 , 3) AS `08`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '09' THEN ghgsl.total ELSE 0 END)/1000 , 3) AS `09`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '10' THEN ghgsl.total ELSE 0 END)/1000 , 3) AS `10`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '11' THEN ghgsl.total ELSE 0 END)/1000 , 3) AS `11`,
							ROUND(SUM(CASE WHEN SUBSTRING(ghgs.ghg_month, 6, 2) = '12' THEN ghgsl.total ELSE 0 END)/1000 , 3) AS `12`
							FROM  
								ghg_sheets ghgs
							JOIN ghg_tmpls ghgtmpls ON ghgs.ghg_tmpl_id = ghgtmpls.id
							JOIN ghg_sheet_lines ghgsl ON ghgsl.ghg_sheet_id = ghgs.id AND ghgsl.deleted_by is NULL
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
				'title' => 'Half Year',
				'dataIndex' => 'half_year',
				'hasListenTo' => true,
				'allowClear' => true,
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
			],
		];
	}

	private function groupValuesOfMonth($data){
		foreach ($data as $key => &$value){
			for ($i = 1; $i <= 12; $i++) {
				$monthKey = str_pad($i, 2, '0', STR_PAD_LEFT);
				if(!isset($value[$monthKey])) continue;
				$value['months'][$monthKey] = (float)str_replace(',', '', $value[$monthKey]);
				unset($value[$monthKey]); 
			}
			$value['total_months'] = array_sum($value['months']);
		}	
		return $data;	
	}

	private function sumColumns($data)
	{
		$result = [];
		foreach ($data as $row) {
			foreach ($row as $key => $value) {
				if (!isset($result[$key])) {
					$result[$key] = 0;
				}
				$result[$key] += (float)str_replace(',', '', $value);
			}
		}
		return $result;
	}

	public function sumValueInMetricGroup($data)
	{
		foreach ($data as $key => &$items) {
			if (count($items) > 1) {
				$dataIndexByMonths = array_column($items, 'months');
				$lastItem = end($items);
				$lastItem['months'] = ArrayReport::sumAndMergeItems($dataIndexByMonths);
				$lastItem['total_months'] = array_sum($dataIndexByMonths);
				$items = $lastItem;
			}
		}
		return $data;
	}


	public function changeDataSource($dataSource, $params)
	{
		$primaryData = (new Ghg_sheet_dataSource())->getDataSource($params);
		$primaryData =  Report::convertToType($primaryData);
		$groupByGhgTmplId =  Report::groupArrayByKey($dataSource, 'ghg_tmpls_id');
		// dd($groupByGhgTmplId);
		$totalMonthsOfMetricType = [];
		$dataOfEachMonths = [];
		foreach ($primaryData as $key => &$items){
			if (isset($items['ghg_tmpl_id'])){
				if(isset($groupByGhgTmplId[$items['ghg_tmpl_id']])){
					$dataIndex = $groupByGhgTmplId[$items['ghg_tmpl_id']];
					$dataIndex = self::groupValuesOfMonth($dataIndex);
					$totalMonths = array_sum(array_column($dataIndex, 'total_months'));
					$dataOfEachMonths[] = array_column($dataIndex, 'months');
					
					$totalMonthsOfMetricType[] = $totalMonths;

					$items['children_metrics'] = $dataIndex;

					// group by ghg_metric_type_2_name
					$groupByMetric0and1 = Report::groupArrayByKey($dataIndex, 'ghg_metric_type_2_name');
					$groupByMetric0and1 = $this->sumValueInMetricGroup($groupByMetric0and1);
					$items['group_by_metric0and1'] = array_values($groupByMetric0and1);
				}
			}
		}
		$dataOfEachMonths = array_merge(...$dataOfEachMonths);
		$totalValueEachMonth = $this->sumColumns($dataOfEachMonths);
			
		// dd($primaryData);
		$dataSource = $primaryData;
		// dd($primaryData);
		
		$dataSource =  Report::convertToType($dataSource);
		$dataSource = DocumentReport::groupMonths($dataSource);

		$groupByScope = Report::groupArrayByKey($dataSource, 'scope_id');
		$groupByScope = ['scopes' => array_map(fn ($item) => Report::groupArrayByKey($item, 'ghgcate_id'), $groupByScope)];
		$groupByScope['totalEmissionMetricType'] = array_sum($totalMonthsOfMetricType);
		$groupByScope['totalEmissionMetricTypeEachMonth'] = $totalValueEachMonth;


		$data['tableDataSource'] = $groupByScope;
		$data['tableSetting'] = $this->createInfoToRenderTable($groupByScope);
		// dd($data);	
		return collect($data);
	}


	protected function getDefaultValueParams($params, $request)
	{
		$params['year'] = $this->year;
		return $params;
	}


	public function createInfoToRenderTable($dataSource)
	{
		if(!isset($dataSource['scopes'])) return [];
		$info = [];
		$totalLine = 2;
		foreach ($dataSource['scopes'] as $k => $items) {
			$num = 0;
			$emptyChildrenMetrics = 0;
			foreach ($items as $values){
				$item = last($values);
				$ghgcate_id = $item['ghgcate_id'];
				$countLv2 = Report::countChildrenItemsByKey($values);
				$info[$k][$ghgcate_id]['scope_rowspan_lv2'] = $countLv2;
				foreach ($values  as $index => $val){
					$item = $val['children_metrics'] ?? [];
					$ghg_tmpl_id = $val['ghg_tmpl_id'];
					$info[$k][$ghgcate_id][$ghg_tmpl_id]['scope_rowspan_lv3'] = (count($item) ? count($item) : 1);
					$info[$k][$ghgcate_id][$ghg_tmpl_id]['index_children_metric'] = $index;

					if(isset($val['children_metrics'])) {
						$count = count($val['children_metrics']);
						$num += $count;
					} else{
						$emptyChildrenMetrics += 1;
					}
				}
			}
			$info[$k]['scope_rowspan_lv1'] = $num +$emptyChildrenMetrics;
			$totalLine += $num + $emptyChildrenMetrics;
		}
		$info['total_line'] = $totalLine;
		// dump($info, $dataSource);
		return $info;
	}
}
