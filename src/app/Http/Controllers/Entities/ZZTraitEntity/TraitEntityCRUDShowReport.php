<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\Workflow\LibApps;
use App\Models\Rp_report;
use Illuminate\Http\Request;

trait TraitEntityCRUDShowReport
{
    protected $entity_type;
	protected $reportType2 = 'report2';
	public function showReport(Request $request, $id,  $trashed)
	{
		$report = Rp_report::find($id)->getDeep();
		$pages = $report->getPages->sortBy('order_no');
		$requestInput = $request->input();
		//update per_page of table in reports
		if (isset($requestInput['action']) && $requestInput['action'] =='updateReport2') {
			$request->merge([
				'entity_type' => $report->entity_type,
				'entity_type2' => $this->reportType2,
			]);
			(new UpdateUserSettings())($request);
			return redirect()->to(route('rp_reports.show', $requestInput['report_id']));
		}

		return view('dashboards.pages.entity-show-report', [
			'appName' => LibApps::getFor($this->type)['title'],
			'report' => $report,
			'pages' => $pages,
			'reportId' => $id,
			'paramsUrl' => $requestInput,
		]);
	}
}
