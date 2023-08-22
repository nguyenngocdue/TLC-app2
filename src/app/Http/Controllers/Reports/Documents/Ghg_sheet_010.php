<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
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
		
		$sql =  "SELECT 
			#SUBSTR(ghgsh.ghg_month, 1, 7) AS ghgsh_month
			#,ghgsh.id AS ghgsh_id
			#,ghgsh.name AS ghgsh_name
			
			ghgsh.ghg_tmpl_id AS ghgsh_tmpl_id
			,ghgtmpl.name AS ghgsh_tmpl_name
			,ghgsh.total AS ghgsh_total
			FROM ghg_sheets ghgsh, ghg_tmpls ghgtmpl
			WHERE  1 = 1 
			AND SUBSTR(ghgsh.ghg_month, 1, 7) >= '$fromMonth' 
			AND SUBSTR(ghgsh.ghg_month, 1, 7) <= '$toMonth'
			AND ghgtmpl.id = ghgsh.ghg_tmpl_id
			GROUP BY  ghgsh_tmpl_id, ghgsh_total
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
				'title' => 'Date',
				'dataIndex' => 'picker_date',
				'renderer' => 'picker_date',
				'allowClear' => true,
			],
		];
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
}
