<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocumentController;
use App\Http\Controllers\Reports\TraitForwardModeReport;

class Ghg_sheet_010 extends Report_ParentDocumentController
{

	use TraitForwardModeReport;
	protected $viewName = 'document-ghg-summary-report';

	private function getCurrentDateRange() {
		$currentYearMonth = date("Y-m");
		$sixMonthsAgo = date("Y-m", strtotime("-6 months"));
		return ['from' => $sixMonthsAgo, 'to' => $currentYearMonth];
	}

	private function parseDateRange($dateRange) {
		$parts = explode(" - ", $dateRange);
		list($fromDay, $fromMonth, $fromYear) = explode("/", $parts[0]);
		$fromDate = date("Y-m", strtotime("$fromYear-$fromMonth-$fromDay"));
		list($toDay, $toMonth, $toYear) = explode("/", $parts[1]);
		$toDate = date("Y-m", strtotime("$toYear-$toMonth-$toDay"));
		return ['from' => $fromDate, 'to' => $toDate];
	}

	public function getSqlStr($modeParams)
	{

		$defaultMonth = $this->getCurrentDateRange();
		$fromMonth = $defaultMonth['from'];
		$toMonth = $defaultMonth['to'];
		if(isset($modeParams['picker_date'])){
			$arrayMonth = $this->parseDateRange($modeParams['picker_date']);
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

	public function getTableColumns($dataSource, $modeParams)
	{
		return [[]];
	}

	protected function getParamColumns()
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

	protected function enrichDataSource($dataSource, $modeParams)
	{
		return collect($dataSource);
	}
}
