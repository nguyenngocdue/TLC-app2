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
		$originalRpId = $userInput['original_rp'];
		$originalReport = Rp_report::find($originalRpId);
		$entityType = $originalReport->entity_type;
		$entityType2 = 'report2';

		$popupRpId = $userInput['pop_up_report_id'];

		$popupRp = Rp_report::find($popupRpId);

		// set value when call onclick 
		$fieldToFilter = $userInput['data_index_label'];
		$valueToFilter = $userInput['label_id'];
		
		$filterPrams = CurrentUser::getSettings()[$entityType][$entityType2][$originalRpId];
		$filterPrams[$fieldToFilter] = $valueToFilter;

		$dataset = $userInput["dataset"];
		$filterPrams["dataset"] = $dataset;


		$pagesOfPopupRp = $popupRp->getPages->sortBy('order_no');
		$results = [];
		foreach ($pagesOfPopupRp as $key => $page) {
			if(!$page->is_active) continue;
			$blade = '<x-reports2.report-page :page="$page" :report="$report" :currentParams="$currentParams" ></x-reports2.report-page>'; 
			if(($key + 1) != count($pagesOfPopupRp))    {
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
