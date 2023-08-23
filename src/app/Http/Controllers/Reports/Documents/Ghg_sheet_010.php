<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Utils\Support\DocumentReport;
use App\Utils\Support\Report;

class Ghg_sheet_010 extends Report_ParentDocument2Controller
{

	use TraitForwardModeReport;
	protected $viewName = 'document-ghg-summary-report';

	public function getSqlStr($params)
	{
		$defaultMonth = $this->getCurrentDateRange();
		$fromMonth = $defaultMonth['from'];
		$toMonth = $defaultMonth['to'];
		if(isset($params['picker_date']) && $params['picker_date']){
			$arrayMonth = $this->parseDateRange($params['picker_date']);
			$fromMonth = $arrayMonth['from'];
			$toMonth = $arrayMonth['to'];
		}
		
		$sql =  " SELECT infghgsh.*,
							ghgsh_totals.`01`, ghgsh_totals.`02`, ghgsh_totals.`03`,
							ROUND(ghgsh_totals.`01` + ghgsh_totals.`02` + ghgsh_totals.`03`,2) AS total_12_months
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
						SUM(CASE WHEN SUBSTR(ghgsh.ghg_month, 6, 2) = '03' THEN ghgsh.total ELSE 0 END) AS `03`
						FROM ghg_sheets ghgsh 
						WHERE 1 = 1
						AND SUBSTR(ghgsh.ghg_month, 1, 4) = '2023'
						GROUP BY ghgsh_tmpl_id
					) ghgsh_totals ON infghgsh.ghg_tmpl_id = ghgsh_totals.ghgsh_tmpl_id
					ORDER BY ghgcate_id, ghg_tmpl_id
					;
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
			],
			[
				'title' => 'Month',
				'dataIndex' => 'only_month',
				'allowClear' => true,
				'multiple' => true,
			],
		];
	}

	public function changeDataSource($dataSource){
		$dataSource =  Report::convertToType($dataSource->toArray());
		$groupByScope = Report::groupArrayByKey($dataSource, 'scope_id');
		$groupByScope = array_map(fn($item) => Report::groupArrayByKey($item, 'ghgcate_id'), $groupByScope);
		// dd($groupByScope);
		return collect($groupByScope);
	}


    protected function getDefaultValueParams($params)
    {
        $a = 'picker_date';
        $pickerDate = Report::createDefaultPickerDate();
        if (Report::isNullParams($params)) {
            $params[$a] = $pickerDate;
        }
        return $params;
    }

	public function createInfoToRenderTable($dataSource){
		$info = [];
		foreach ( $dataSource as $k1 => $values1){
			$array = [];
			$array['scope_rowspan'] = DocumentReport::countLastItems($values1);
			foreach($values1 as $k2 => $values2) {
				$array['scope_rowspan_children_'.$k2] = DocumentReport::countLastItems($values2);
			}
			$info[$k1] = $array;
		}
		// dd($info);
		return $info;
	}
}
