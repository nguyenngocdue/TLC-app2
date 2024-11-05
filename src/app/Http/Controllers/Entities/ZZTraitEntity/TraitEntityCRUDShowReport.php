<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\Workflow\LibApps;
use App\Models\Rp_report;
use App\View\Components\Reports2\TraitReportFilter;
use App\View\Components\Reports2\TraitSharedReportUtilities;
use Illuminate\Http\Request;

trait TraitEntityCRUDShowReport
{
	use TraitReportFilter;
	use TraitSharedReportUtilities;

	protected $entity_type;
	protected $reportType2;
	protected $report;
	
	public function showReport(Request $request, $id)
	{
		$this->reportType2 = 'report2';
		$this->report = Rp_report::find($id)->getDeep();
		$report = $this->report;
		$pages = $report->getPages->sortBy('order_no');

		$requestInput = $request->input();
		//update per_page of table in reports
		if (isset($requestInput['action']) && $requestInput['action'] == 'updateReport2') {
			$request->merge([
				'entity_type' =>  $report->entity_type,
				'entity_type2' => $this->reportType2,
			]);
			(new UpdateUserSettings())($request);
			return redirect()->to(route('rp_reports.show', $requestInput['report_id']));
		}
		$currentParams = $this->currentParamsReport($report);

		return view('dashboards.pages.entity-show-report', [
			'appName' => LibApps::getFor($this->type)['title'],
			'report' =>  $report,
			'pages' => $pages,
			'reportId' => $id,
			'paramsUrl' => $requestInput,
			'currentParams' => $currentParams
		]);
	}
}
