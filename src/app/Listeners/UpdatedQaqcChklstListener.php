<?php

namespace App\Listeners;

use App\Events\UpdatedQaqcChklstEvent;
use App\Http\Services\ExternalInspector\UpdateProdRoutingService;
use App\Http\Services\ExternalInspector\UpdateQaqcInspTmplService;
use App\Http\Services\ExternalInspector\UpdateSubProjectService;
use App\Models\Qaqc_insp_chklst;
use Illuminate\Support\Facades\Log;

class UpdatedQaqcChklstListener //implements ShouldQueue // MUST NOT QUEUE
{
    // use Queueable;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private UpdateSubProjectService $subProjectService,
        private UpdateQaqcInspTmplService $qaqcInspTmplService,
        private UpdateProdRoutingService $prodRoutingService,
    ) {
        //
        // Log::info('UpdatedQaqcChklstListener constructor');
    }

    public function handle(UpdatedQaqcChklstEvent $event)
    {
        // $ids = $this->getIdChklst($event->subProjectId);
        $bookId = $event->sheet->qaqc_insp_chklst_id;
        $book = Qaqc_insp_chklst::find($bookId);
        // Log::info($book);

        $newSignOffList = $event->newSignOffList;
        $nominatedListFn = $event->nominatedListFn;

        $newCouncilList = $event->newCouncilList;
        $councilListFn = $event->councilListFn;

        $newShippingAgentList = $event->newShippingAgentList;
        $shippingAgentListFn = $event->shippingAgentListFn;

        $theTemplate = $book->getQaqcInspTmpl()->first();
        // Log::info($theTemplate);
        $allTemplateSheets = $theTemplate->getSheets()->get();
        // Log::info($allTemplateSheets);
        $allSheets = $book->getSheets()->get();
        // Log::info($allSheets);

        // $templateIds = $allTemplateSheets->pluck('id');
        // // Log::info($templateIds);
        // $sheetIds = $allSheets->pluck('progress', 'qaqc_insp_tmpl_sht_id',);
        // // Log::info($sheetIds);

        // $array = [];
        // foreach ($templateIds as $id) $array[$id] = 0;
        // foreach ($sheetIds as $tmplId => $progress) $array[$tmplId] = $progress;
        // // Log::info($array);
        // $newProgress = array_sum($array) / count($array);

        $allTemplateSheetCount = $allTemplateSheets->count();
        if ($allTemplateSheetCount == 0) {
            $newProgress = 100;
        } else {
            $allNaCount = $allSheets->filter(function ($sheet) {
                return $sheet->status == 'not_applicable';
            })->count();
            $allSheetsCount = $allSheets->filter(function ($sheet) {
                return in_array($sheet->status, ['inspected', 'audited']);
            })->count();
            // Log::info("TU SO " . $allSheetsCount);
            // Log::info("MAU SO " . ($allTemplateSheetCount - $allNaCount));
            $newProgress = $allSheetsCount / ($allTemplateSheetCount - $allNaCount) * 100;
        }

        $book->progress = $newProgress;
        $book->save();

        // Log::info('UpdatedQaqcChklstListener 3 Sub-Services calling...');
        // Log::info("SubProjectId " . $book->sub_project_id);
        // Log::info("TmplId " . $book->qaqc_insp_tmpl_id);
        // Log::info("ProdOrderId " . $book->getProdOrder->prod_routing_id);
        // Log::info($nominatedListFn);
        // dd($nominatedListFn);

        // Log::info($newSignOffList);
        // Log::info($nominatedListFn);

        $this->subProjectService->update($book->sub_project_id, $newSignOffList, $nominatedListFn, "getExternalInspectorsOfSubProject");
        $this->prodRoutingService->update($book->prod_routing_id, $newSignOffList, $nominatedListFn, "getExternalInspectorsOfProdRouting");
        $this->qaqcInspTmplService->update($book->qaqc_insp_tmpl_id, $newSignOffList, $nominatedListFn, "getExternalInspectorsOfQaqcInspTmpl");

        $this->subProjectService->update($book->sub_project_id, $newShippingAgentList, $shippingAgentListFn, "getShippingAgentsOfSubProject");
        $this->prodRoutingService->update($book->prod_routing_id, $newShippingAgentList, $shippingAgentListFn, "getShippingAgentsOfProdRouting");
        $this->qaqcInspTmplService->update($book->qaqc_insp_tmpl_id, $newShippingAgentList, $shippingAgentListFn, "getShippingAgentsOfQaqcInspTmpl");

        $this->subProjectService->update($book->sub_project_id, $newCouncilList, $councilListFn, "getCouncilMembersOfSubProject");
        $this->prodRoutingService->update($book->prod_routing_id, $newCouncilList, $councilListFn, "getCouncilMembersOfProdRouting");
        $this->qaqcInspTmplService->update($book->qaqc_insp_tmpl_id, $newCouncilList, $councilListFn, "getCouncilMembersOfQaqcInspTmpl");
    }
}
