<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\Rp_report;
use Illuminate\Http\Request;

trait TraitEntityCRUDShowReport
{
    protected $entity_type;
	protected $entityType2 = 'report2';
	public function showReport(Request $request, $id,  $trashed)
	{
		$report = Rp_report::find($id)->getDeep();
		$pages = $report->getPages;
		$paramsUrl = $request->input();

		return view('dashboards.pages.entity-show-report', [
			'appName' => LibApps::getFor($this->type)['title'],
			'report' => $report,
			'pages' => $pages,
			'reportId' => $id,
			'paramsUrl' => $paramsUrl,
		]);
	}
}
