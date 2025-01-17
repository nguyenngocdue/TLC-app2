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
            if (in_array($status, ["in_progress"])) {
                $tmp = $prodSequence->total_hours;
                if ($tmp > $avg) $tmp = $avg;
                $inProgressTotalHours[] = 0.8 * $tmp;
            }
        }

        $routing_detail_indexed = $routing_detail->keyBy('prod_routing_link_id')->pluck('avg_actual_hours', 'prod_routing_link_id');
        // Log::info($routing_detail_indexed);
        $sequence_indexed = $allProdSequences->keyBy('prod_routing_link_id')->pluck('status', 'prod_routing_link_id');
        // Log::info($sequence_indexed);

        foreach ($routing_detail_indexed as $routingLinkId => $avg_actual_hours) {
            $status = $sequence_indexed[$routingLinkId] ?? '';
            if (!in_array($status, LibStatuses::$naArray)) $totalHours[] = $avg_actual_hours;
        }

        // Log::info($finishedTotalHours);
        // Log::info($inProgressTotalHours);
        // Log::info($totalHours);

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

        $currentProgress = $prodOrder->prod_sequence_progress;
        // Log::info("Before " . $prodOrder->prod_sequence_progress);
        // Log::info($allProdSequences->count());//103
        // Log::info($routingId);//123
        $newProgress = $this->getProdSequenceProgress($allProdSequences, $routingId);

        //Only update when new progress is greater than current progress
        //Sometime algorithm change and pull new progress down, we dont want that happen in client reports.
        if ($newProgress > $currentProgress) {
            $dataUpdated['prod_sequence_progress'] = $this->getProdSequenceProgress($allProdSequences, $routingId);
            $prodOrder->update($dataUpdated);
        }
    }
}
