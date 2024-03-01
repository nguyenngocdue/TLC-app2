<?php

namespace App\Http\Services\ExternalInspector;

use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_tmpl;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UpdateQaqcInspTmplService
{
    function update($qaqcInspTmplId, $newSignOffList, $nominatedListFn)
    {
        $allLists = Qaqc_insp_chklst::query()
            ->where("qaqc_insp_tmpl_id", $qaqcInspTmplId)
            ->with(['getSheets'])
            ->get();

        $result = [];
        foreach ($allLists as $list) {
            $allSheets = $list->getSheets;
            // Oracy::attach("getMonitors1()", $allSheets);
            foreach ($allSheets as $sheet) {
                $result[] = $sheet->{$nominatedListFn}()->pluck('id')->toArray();
            }
        }

        $result = array_values(array_unique(Arr::flatten($result)));
        if (is_array($newSignOffList)) $result = array_unique([...$result, ...$newSignOffList]);

        $item = Qaqc_insp_tmpl::find($qaqcInspTmplId);
        $item->syncCheck("getExternalInspectorsOfQaqcInspTmpl", \App\Models\User::class, $result);
        // Log::info("UpdateQaqcInspTmplService" . $qaqcInspTmplId);
        // Log::info($result);
    }
}
