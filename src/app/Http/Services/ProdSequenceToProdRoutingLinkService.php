<?php

namespace App\Http\Services;

use App\Models\Prod_routing_detail_sub_project;
use App\Models\Prod_sequence;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Log;

class ProdSequenceToProdRoutingLinkService
{
    private function getTheItem($prodSequence)
    {
        $prodRoutingDetailId = $prodSequence->getProdRoutingDetail->id;
        $subProjectId = $prodSequence->sub_project_id;
        $prodRoutingId = $prodSequence->prod_routing_id;
        $prodRoutingLinkId = $prodSequence->prod_routing_link_id;

        $params = [
            'sub_project_id' => $subProjectId,
            'prod_routing_detail_id' => $prodRoutingDetailId,
        ];

        $items = Prod_routing_detail_sub_project::query()->where($params)->get();

        if ($items->count()) {
            // Log::info("In DB");
            $item = $items[0];
        } else {
            // Log::info("Create new");
            $cuid = CurrentUser::id();
            $item = Prod_routing_detail_sub_project::create($params + [
                'prod_routing_id' => $prodRoutingId,
                'prod_routing_link_id' => $prodRoutingLinkId,
                'owner_id' => $cuid,
            ]);
        }

        return $item;
    }

    private function getAllRelevantSequences($params)
    {
        $result = Prod_sequence::query()
            ->where($params)
            ->whereIn('status', ['finished'])
            ->get();
        // Log::info($result);
        return $result;
    }

    private function updateData($item, $prodSequence)
    {
        $params =  [
            'sub_project_id' => $prodSequence->sub_project_id,
            'prod_routing_id' => $prodSequence->prod_routing_id,
            'prod_routing_link_id' => $prodSequence->prod_routing_link_id,
        ];

        $allSequences = $this->getAllRelevantSequences($params);
        // Log::info($allSequences[0]);
        $sheet_count = $allSequences->count();
        // Log::info($sheet_count);
        $avg_man_power = 0;
        $avg_total_uom = 0;
        $avg_min = 0;
        if ($sheet_count) {
            $avg_man_power = $allSequences->avg(function ($item) {
                return $item['worker_number'];
            });
            $avg_total_uom = $allSequences->avg(function ($item) {
                return $item['total_uom'];
            });
            $avg_min = $allSequences->avg(function ($item) {
                return $item['total_hours'] * 60;
            });
        }

        $params = [
            "sheet_count" => $sheet_count,
            "avg_man_power" => round($avg_man_power, 2),
            "avg_total_uom" => round($avg_total_uom, 2),
            "avg_min" => round($avg_min, 2),
            "avg_min_uom" => $avg_total_uom ? round($avg_min / $avg_total_uom, 2) : 0,
        ];
        $item->update($params);
        // Log::info($item);
    }

    public function update($id)
    {
        $prodSequence = Prod_sequence::query()
            // ->with(['getProdRoutingDetail']) //Eager Loaf failed
            ->find($id);


        $item = $this->getTheItem($prodSequence);
        $this->updateData($item, $prodSequence);
    }
}
