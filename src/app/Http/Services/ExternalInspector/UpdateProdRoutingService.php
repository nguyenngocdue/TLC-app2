<?php

namespace App\Http\Services\ExternalInspector;

use App\Models\Prod_routing;
use App\Models\Qaqc_insp_chklst;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UpdateProdRoutingService
{
    function update($prod_routing_id, $newSignOffList, $nominatedListFn, $targetFn)
    {
        $allLists = Qaqc_insp_chklst::query()
            ->where("prod_routing_id", $prod_routing_id)
            ->with(['getSheets'])
            ->get();

        $result = [];
        foreach ($allLists as $list) {
            $allSheets = $list->getSheets;
            foreach ($allSheets as $sheet) {
                $result[] = $sheet->{$nominatedListFn}()->pluck('id')->toArray();
            }
        }

        $result = array_values(array_unique(Arr::flatten($result)));
        if (is_array($newSignOffList)) $result = array_unique([...$result, ...$newSignOffList]);
        $result = array_map(fn ($id) => +$id, $result);

        $item = Prod_routing::find($prod_routing_id);
        $item->syncCheck($targetFn, \App\Models\User::class, $result);
        // Log::info("UpdateProdRoutingService" . $prod_routing_id);
        // Log::info($result);
    }
}
