<?php

namespace App\Http\Services\ExternalInspector;

use App\Models\Qaqc_insp_chklst;
use App\Models\Sub_project;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UpdateSubProjectService
{
    function update($subProjectId, $nominatedListFn)
    {
        $allLists = Qaqc_insp_chklst::query()
            ->where("sub_project_id", $subProjectId)
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

        $item = Sub_project::find($subProjectId);
        $item->syncCheck("getExternalInspectorsOfSubProject", \App\Models\User::class, $result);
        // Log::info("UpdateSubProjectService" . $subProjectId);
        // Log::info($result);
    }
}
