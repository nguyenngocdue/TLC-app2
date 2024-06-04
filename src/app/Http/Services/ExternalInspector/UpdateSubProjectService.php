<?php

namespace App\Http\Services\ExternalInspector;

use App\Models\Qaqc_insp_chklst;
use App\Models\Sub_project;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UpdateSubProjectService
{
    function update($subProjectId, $newSignOffList, $nominatedListFn, $targetFn)
    {
        $allLists = Qaqc_insp_chklst::query()
            ->where("sub_project_id", $subProjectId)
            ->with(['getSheets'])
            ->get();

        $result = [];
        foreach ($allLists as $list) {
            $allSheets = $list->getSheets;
            foreach ($allSheets as $sheet) {
                $list = $sheet->{$nominatedListFn};
                $result[] = $list->pluck('id')->toArray();
            }
        }

        $result = array_values(array_unique(Arr::flatten($result)));
        if (is_array($newSignOffList)) $result = array_unique([...$result, ...$newSignOffList]);
        $result = array_map(fn ($id) => +$id, $result);

        $item = Sub_project::find($subProjectId);
        // $item->syncCheck($targetFn, \App\Models\User::class, $result);
        $item->{$targetFn}()->syncWithPivotValues($result, ['owner_id' => CurrentUser::id()]);
        // Log::info("UpdateSubProjectService" . $subProjectId);
        // Log::info($result);
    }
}
