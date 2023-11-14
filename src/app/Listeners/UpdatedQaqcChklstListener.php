<?php

namespace App\Listeners;

use App\Events\UpdatedQaqcChklstEvent;
use App\Models\Qaqc_insp_chklst;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;

class UpdatedQaqcChklstListener //implements ShouldQueue //<<No need to queue
{
    // use Queueable;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        //<<This will also output on the web, not only the command
        // $count = count($ids);
        // $result = $success . "/" . $count;
        // if ($success || $count) echo ("The progress of Inspection Checklists have been successfully updated: $result \n");
    }
}
