<?php

namespace App\Listeners;

use App\Events\UpdatedQaqcChklstEvent;
use App\Http\Services\ExternalInspector\UpdateQaqcInspTmplService;
use App\Http\Services\ExternalInspector\UpdateSubProjectService;
use App\Models\Qaqc_insp_chklst;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UpdatedQaqcChklstListener implements ShouldQueue //<<No need to queue
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

        $theTemplate = $book->getQaqcInspTmpl()->first();
        $allTemplateSheets = $theTemplate->getSheets()->get();
        $allSheets = $book->getSheets()->get();
        // Log::info($allTemplateSheets);
        // Log::info($allSheets);

        // Log::info($theTemplate);
        $templateIds = $allTemplateSheets->pluck('id');
        $sheetIds = $allSheets->pluck('progress', 'qaqc_insp_tmpl_sht_id',);
        // Log::info($templateIds);
        // Log::info($sheetIds);

        $array = [];
        foreach ($templateIds as $id) $array[$id] = 0;
        foreach ($sheetIds as $tmplId => $progress) $array[$tmplId] = $progress;
        // Log::info($array);
        $newProgress = array_sum($array) / count($array);

        $book->progress = $newProgress;
        $book->save();

        // Log::info('UpdatedQaqcChklstListener Service calling...');
        $this->subProjectService->update($book->sub_project_id);
        $this->qaqcInspTmplService->update($book->qaqc_insp_tmpl_id);
    }
}
