<?php

namespace App\Http\Services\ExternalInspector;

use App\Models\Qaqc_insp_chklst;
use App\Models\Sub_project;
use Illuminate\Support\Arr;

class UpdateSubProjectService
{
    function update($subProjectId)
    {
        $allLists = Qaqc_insp_chklst::query()
            ->where("sub_project_id", $subProjectId)
            ->with(['getSheets'])
            ->get();

        $result = [];
        foreach ($allLists as $list) {
            $allSheets = $list->getSheets;
            foreach ($allSheets as $sheet) {
                $result[] = $sheet->getMonitors1()->pluck('id')->toArray();
            }
        }

        $result = array_values(array_unique(Arr::flatten($result)));

        $sp = Sub_project::find($subProjectId);
        $sp->syncCheck("getExternalInspectorsOfSubProject", \App\Models\User::class, $result);
    }
}
