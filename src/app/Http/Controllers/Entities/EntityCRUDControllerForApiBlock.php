<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use App\Models\Rp_block;
use App\Models\Rp_page;
use App\Models\Rp_page_block_detail;
use App\Models\Rp_report;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Api\ResponseObject;
use App\View\Components\Reports2\TraitReportFilter;
use App\View\Components\Reports2\TraitReportQueriedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class EntityCRUDControllerForApiBlock extends Controller
{
	function renderBlock(Request $request)
	{
		$userInput = $request->input();
		$originalRpId = $userInput['originalRp'];
		$originalReport = Rp_report::find($originalRpId);
		$entityType = $originalReport->entity_type;

		$popupRpId = $userInput['popupReportId'];

		$popupRp = Rp_report::find($popupRpId);

		// set value when call onclick 
		$fieldToFilter = $userInput['dataIndexLabel'];
		$valueToFilter = $userInput['labelId'];
		$datasetVariable = $userInput['datasetVariable'];
		

		$filterPrams = CurrentUser::getSettings()[$entityType][$originalReport->entityType2][$originalRpId];
		$filterPrams[$fieldToFilter] = $valueToFilter;
		$filterPrams[$datasetVariable] = $userInput[$datasetVariable];


		$pagesOfPopupRp = $popupRp->getPages->sortBy('order_no');
		$results = [];
		foreach ($pagesOfPopupRp as $key => $page) {
			if (!$page->is_active) continue;
			$blade = '<x-reports2.report-page :page="$page" :report="$report" :currentParams="$currentParams" ></x-reports2.report-page>';
			if (($key + 1) != count($pagesOfPopupRp)) {
				$blade .= "<x-renderer.page-break />";
			}
			$results[$key] = Blade::render($blade, [
				'page' => $page,
				'report' => $popupRp,
				'currentParams' => $filterPrams,
			]);
		}
		return ResponseObject::responseSuccess(
			$results,
		);
	}
}
