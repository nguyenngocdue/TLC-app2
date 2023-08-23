<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\DocumentReport;
use App\Utils\Support\Report;

class Ghg_sheet_010 extends Report_ParentDocument2Controller
{

	use TraitForwardModeReport;
    use TraitParamsSettingReport;

	protected $viewName = 'document-ghg-summary-report';
	protected $year = '2032';

	public function getSqlStr($params)
	{
		dump($params);
		$months = range(1,12);
		if (isset($params['quarter_time'])) {
			if($params['quarter_time'] === '1'){
				$months = range(1,3);
			} elseif($params['quarter_time'] === '2'){
				$months = range(4,6);
			}
			elseif($params['quarter_time'] === '3'){
				$months = range(7,9);
			}
			elseif($params['quarter_time'] === '4'){
				$months = range(10,12);
			}
			elseif(isset($params['only_month']) && empty($params['only_month']) && is_null($params['quarter_time'])){
				$months = range(1,12);
			}elseif(!isset($params['only_month']) && is_null($params['quarter_time'])){
				$months = range(1,12);
			} 
		}
		if(isset($params['only_month'])){
			$months = $params['only_month'];
		}
		// if(isset($params['only_month']) && is_null($params['only_month'][0]) && !isset($params['quarter_time'])){
		// 	$months = range(1,12);
		// } 

		dump($months);
		$strSqlMonth = '';
		foreach($months as $month){
			$tableName = 'ghgsh_totals';
			$month = strlen($month) > 1 ? $month : '0'.$month;
			$strSqlMonth .= $tableName.'.'.$month.',';
		}
		$strSumValue = str_replace(',',' + ',trim($strSqlMonth, ','));
		$year = $params['year'];



		$sql =  " SELECT infghgsh.*,$strSqlMonth
						ROUND($strSumValue,2) AS total_months
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
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '01' THEN ghgsh.total ELSE 0 END) AS `01`,
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '02' THEN ghgsh.total ELSE 0 END) AS `02`,
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '03' THEN ghgsh.total ELSE 0 END) AS `03`,
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '04' THEN ghgsh.total ELSE 0 END) AS `04`,
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '05' THEN ghgsh.total ELSE 0 END) AS `05`,
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '06' THEN ghgsh.total ELSE 0 END) AS `06`,
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '07' THEN ghgsh.total ELSE 0 END) AS `07`,
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '08' THEN ghgsh.total ELSE 0 END) AS `08`,
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '09' THEN ghgsh.total ELSE 0 END) AS `09`,
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '10' THEN ghgsh.total ELSE 0 END) AS `10`,
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '11' THEN ghgsh.total ELSE 0 END) AS `11`,
							SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '12' THEN ghgsh.total ELSE 0 END) AS `12`
							FROM ghg_sheets ghgsh 
							WHERE 1 = 1
							AND ghgsh.deleted_by IS NULL
							AND SUBSTR(ghgsh.ghg_month, 1, 4) = '$year'
							GROUP BY ghgsh_tmpl_id
						) ghgsh_totals ON infghgsh.ghg_tmpl_id = ghgsh_totals.ghgsh_tmpl_id
						ORDER BY ghgcate_id, ghg_tmpl_id
					";
		return $sql;
	}

	public function getTableColumns($dataSource, $params)
	{
		return [[]];
	}

	public function getParamColumns($dataSource, $modeType)
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

	public function changeDataSource($dataSource, $params){
		$dataSource =  Report::convertToType($dataSource);
		$dataSource = DocumentReport::groupMonths($dataSource);
		$months = reset($dataSource)['months'];
		$monthlyTotals = ['sum_total_months' => 0.0] + array_fill_keys(array_keys($months), 0.0);
		foreach ($dataSource as $item) {
			$monthlyTotals['sum_total_months'] += $item['total_months'];
			foreach ($item['months'] as $month => $value) {
				$monthlyTotals[$month] += (int)$value;
			}
		}
		$groupByScope = Report::groupArrayByKey($dataSource, 'scope_id');
		$groupByScope = array_map(fn($item) => Report::groupArrayByKey($item, 'ghgcate_id'), $groupByScope);
		$groupByScope['total_emission'] = $monthlyTotals;
		return collect($groupByScope);
	}


    protected function getDefaultValueParams($params,$request)
    {
        $a = 'year';
        if (Report::isNullParams($params)) {
            $params[$a] = $this->year;
        }
        return $params;
    }

	public function createInfoToRenderTable($dataSource){
		$info = [];
		foreach ( $dataSource as $k1 => $values1){
			$array = [];
			$array['scope_rowspan'] = DocumentReport::countLastItems($values1);
			foreach($values1 as $k2 => $values2) {
				if(!isset($values2['months'])) continue;
				$array['months'] = array_keys(reset($values2)['months']);
				$array['scope_rowspan_children_'.$k2] = DocumentReport::countLastItems($values2);
			}
			$info[$k1] = $array;
		}
		// dd($dataSource, $info);
		return $info;
	}
}
