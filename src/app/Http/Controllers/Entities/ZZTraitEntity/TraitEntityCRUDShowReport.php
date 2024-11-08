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

	protected $entityType;
	protected $reportType2 = 'report2';
	protected $report;
	
	public function showReport(Request $request, $id)
	{
		$this->loadReport($id);
        if (!$this->report) {
            return abort(404, 'Report not found');
        }

		$report = $this->report;
		$pages = $report->getPages->sortBy('order_no');

		$requestInput = $request->input();

		//update per_page of table in reports
		if ($this->isUpdateReportAction($requestInput)) {
            return $this->updateReportSettings($request, $requestInput);
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
	

	protected function loadReport($id)
    {
        $this->report = Rp_report::find($id)->getDeep();
    }

	protected function isUpdateReportAction($requestInput)
    {
        return isset($requestInput['action']) && $requestInput['action'] === 'updateReport2';
    }

	protected function updateReportSettings(Request $request, $requestInput)
    {
        $request->merge([
            'entity_type' => $this->report->entity_type,
            'entity_type2' => $this->reportType2,
        ]);

        (new UpdateUserSettings())($request);

        return redirect()->route('rp_reports.show', $requestInput['report_id']);
    }
}
