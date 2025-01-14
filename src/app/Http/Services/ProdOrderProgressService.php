<?php

namespace App\Http\Services;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Prod_routing_detail;
use App\Models\Prod_sequence;
use Illuminate\Support\Facades\Log;

class ProdOrderProgressService
{
    private function getProdSequence($sequenceId)
    {
        return Prod_sequence::query()
            ->with(['getProdOrder' => function ($query) {
                $query->with(['getProdSequences' => function ($query) {
                    $query->with(['getProdRuns']);
                }]);
            }])
            ->find($sequenceId);
    }

    private function getProdSequenceProgress($allProdSequences, $routingId)
    {
        $routing_detail = Prod_routing_detail::query()
            ->where('prod_routing_id', $routingId)
            ->get();
        $avgActualHours = $routing_detail->pluck('avg_actual_hours', 'prod_routing_link_id');

        $finishedTotalHours = [];
        $inProgressTotalHours = [];
        $totalHours = [];
        foreach ($allProdSequences as $prodSequence) {
            $status = $prodSequence->status;
            $avg = $avgActualHours[$prodSequence->prod_routing_link_id];
            if (in_array($status, LibStatuses::$finishedArray)) $finishedTotalHours[] = $avg;
            // if (in_array($status, ["in_progress"])) {
            //     $tmp = $prodSequence->total_hours;
            //     if ($tmp > $avg) $tmp = $avg;
            //     $inProgressTotalHours[] = 0.8 * $tmp;
            // }
            if (!in_array($status, LibStatuses::$naArray)) $totalHours[] = $avg;
        }

        // Log::info($finishedTotalHours);
        // Log::info($totalHours);
        // Log::info($inProgressTotalHours);

        $tu = array_sum($finishedTotalHours) + array_sum($inProgressTotalHours);
        $mau = array_sum($totalHours);

        if ($mau == 0) return -1;
        // return 99;
        return round(100 * $tu / $mau, 2);
    }

    public function update($sequenceId)
    {
        // Log::info("ID - " . $sequenceId);
        $prodOrder = $this->getProdSequence($sequenceId)->getProdOrder;
        // Log::info($prodOrder);
        $allProdSequences = $prodOrder->getProdSequences;
        $routingId = $prodOrder->prod_routing_id;

        $before = $prodOrder->prod_sequence_progress;
        // Log::info("Before " . $prodOrder->prod_sequence_progress);
        // Log::info($allProdSequences->count());//103
        // Log::info($routingId);//123
        $dataUpdated['prod_sequence_progress'] = $this->getProdSequenceProgress($allProdSequences, $routingId);
        // Log::info("After " . $dataUpdated['prod_sequence_progress']);
        $after = $dataUpdated['prod_sequence_progress'];

        $prodOrder->update($dataUpdated);
        if ($before != $after) {
            // dump("ProdOrderProgressService updated: #" . $prodOrder->id . " from " . $before . " to " . $after);
        }
    }
}
