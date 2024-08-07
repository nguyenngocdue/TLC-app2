<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\Rp_report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

trait TraitEntityCRUDShowReport
{


	public function showReport(Request $request, $id,  $trashed)
	{
		$report = Rp_report::find($id)->getDeep();
		$pages = $report->getPages->sortBy('order_no');
		$filterModes = $report->getFilterModes->sortBy('order_no');
		$filterDetails = $report->getFilterDetails->sortBy('order_no');

		$paramsUrl = $request->input();
		if ($paramsUrl) {
			Session::put('paramsUrl', $paramsUrl);
			return redirect()->to($request->getPathInfo());
		}
		$paramsUrl = Session::get('paramsUrl', []);

		return view('dashboards.pages.entity-show-report', [
			'appName' => LibApps::getFor($this->type)['title'],
			'report' => $report,
			'pages' => $pages,
			'reportId' => $id,
			'filterModes' => $filterModes,
			'filterDetails' => $filterDetails,
			'paramsUrl' => $paramsUrl,
		]);
	}
}
