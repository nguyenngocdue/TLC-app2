<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\Rp_report;

trait TraitEntityCRUDShowReport
{
	public function showReport($id, $trashed)
	{
		$report = Rp_report::find($id)->getDeep();
		$pages = $report->getPages;
		return view('dashboards.pages.entity-show-report', [
			'appName' => LibApps::getFor($this->type)['title'],
			'report' => $report,
			'pages' => $pages
		]);
	}
}
