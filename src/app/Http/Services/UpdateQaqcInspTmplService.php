<?php

namespace App\Http\Services;

use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Sub_project;
use Illuminate\Support\Arr;

class UpdateQaqcInspTmplService
{
    function update($qaqcInspTmplId)
    {
        $allLists = Qaqc_insp_chklst::query()
            ->where("qaqc_insp_tmpl_id", $qaqcInspTmplId)
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

        $sp = Qaqc_insp_tmpl::find($qaqcInspTmplId);
        $sp->syncCheck("getExternalInspectorsOfQaqcInspTmpl", \App\Models\User::class, $result);
    }
}
