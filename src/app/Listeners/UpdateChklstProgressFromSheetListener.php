<?php

namespace App\Listeners;

use App\Events\UpdateChklstProgressEvent;
use App\Models\Qaqc_insp_chklst;
use App\Models\Sub_project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Console\Concerns\InteractsWithIO;

class UpdateChklstProgressFromSheetListener implements ShouldQueue
{
    use Queueable;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */




    private function calculateProgressOfSheets($idChklst)
    {
        $sql = "SELECT
        SUM(count_line_in_sheet) AS total_line
        ,SUM(line_percent) AS total_line_percent
        ,(SUM(line_percent) / SUM(count_line_in_sheet)) AS progress_chklst
        FROM (SELECT
            chkl.qaqc_insp_chklst_sht_id AS sheet_id
            ,chks.description AS sheet_description
            ,chks.qaqc_insp_chklst_id AS chklst_id
            ,COUNT(chkl.qaqc_insp_chklst_sht_id) AS count_line_in_sheet
            ,chks.progress AS sheet_progress
            ,ROUND(chks.progress*COUNT(chkl.qaqc_insp_chklst_sht_id)/100, 2)*chks.progress AS line_percent
            FROM qaqc_insp_chklst_lines chkl, qaqc_insp_chklst_shts chks
            WHERE 1 = 1
            AND chkl.qaqc_insp_chklst_sht_id =  chks.id";
        $sql .= "\n AND chks.qaqc_insp_chklst_id = " . $idChklst;
        $sql .= "\n GROUP BY sheet_id) AS chkshtb1";
        $sqlData = DB::select(DB::raw($sql));
        return collect($sqlData);
    }


    private  function getIdChklst($subProjectId)
    {
        $prodOrder = Sub_project::find($subProjectId)->getProdOrders;
        $result = [];
        foreach ($prodOrder as $po) {
            foreach ($po->getQaqcInspChklsts as $chklst) {
                $result[] = $chklst->id;
            }
        }
        return $result;
    }

    public function handle(UpdateChklstProgressEvent $event)
    {
        $ids = $this->getIdChklst($event->subProjectId);
        $success = 0;
        foreach ($ids as $idChklst) {
            $chklst = Qaqc_insp_chklst::find($idChklst);
            $sqlData = $this->calculateProgressOfSheets($idChklst);
            $newProgress = $sqlData->toArray()[0]->progress_chklst;
            if ($chklst->update(['progress' => $newProgress])) $success++;
        }
        //<<This will also output on the web, not only the command
        // $count = count($ids);
        // $result = $success . "/" . $count;
        // if ($success || $count) echo ("The progress of Inspection Checklists have been successfully updated: $result \n");
    }
}
