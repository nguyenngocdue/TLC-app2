<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\Rp_report;
use Illuminate\Http\Request;

trait TraitEntityCRUDShowReport
{
	public function showReport(Request $request, $id,  $trashed)
	{
		// dd($request);
		$report = Rp_report::find($id)->getDeep();
		$pages = $report->getPages->sortBy('order_no');
		$filterModes = $report->getFilterModes->sortBy('order_no');
		$filterDetails = $report->getFilterDetails->sortBy('order_no');

		return view('dashboards.pages.entity-show-report', [
			'appName' => LibApps::getFor($this->type)['title'],
			'report' => $report,
			'pages' => $pages,
			'reportId' => $id,
			'filterModes' => $filterModes,
			'filterDetails' => $filterDetails
		]);
	}
}
