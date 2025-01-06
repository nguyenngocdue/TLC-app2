<?php

namespace App\Http\Services;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Prod_routing_detail;
use App\Models\Prod_sequence;
use Illuminate\Support\Facades\Log;

class ProdSequenceToProdOrderService
{
    use TraitProdSequenceAdvancedMetrics;

    private function getProdSequence($id)
    {
        return Prod_sequence::query()
            ->with(['getProdOrder' => function ($query) {
                $query->with(['getProdSequences' => function ($query) {
                    $query->with(['getProdRuns']);
                }]);
            }])
            ->find($id);
    }

    // private function getProdSequenceProgress($allProdSequences)
    // {
    //     $finishedTotalHours = 0;
    //     $totalHours = 0;
    //     foreach ($allProdSequences as $prodSequence) {
    //         $status = $prodSequence->status;
    //         if (in_array($status, LibStatuses::$finishedArray)) $finishedTotalHours++;
    //         if (!in_array($status, LibStatuses::$naArray)) $totalHours++;
    //     }

    //     return round(100 * $finishedTotalHours / $totalHours, 2);
    // }

    private function getProdSequenceProgress($allProdSequences, $routingId)
    {
        $routing_detail = Prod_routing_detail::query()
            ->where('prod_routing_id', $routingId)
            ->get();
        // Log::info($routing_detail);
        $avgActualHours = $routing_detail->pluck('avg_actual_hours', 'prod_routing_link_id');
        // Log::info($avgActualHours);

        $finishedTotalHours = [];
        $totalHours = [];
        foreach ($allProdSequences as $prodSequence) {
            $status = $prodSequence->status;
            // Log::info($prodSequence);
            // Log::info("Routing detail ID: " . $prodSequence->prod_routing_link_id);
            if (in_array($status, LibStatuses::$finishedArray)) $finishedTotalHours[] = $avgActualHours[$prodSequence->prod_routing_link_id];
            if (!in_array($status, LibStatuses::$naArray)) $totalHours[] = $avgActualHours[$prodSequence->prod_routing_link_id];
        }

        // Log::info($finishedTotalHours);
        // Log::info($totalHours);

        // Log::info("New progress: " . round(100 * $finishedTotalHours / $totalHours, 2));
        return round(100 * array_sum($finishedTotalHours) / array_sum($totalHours), 2);
    }

    public function update($id)
    {
        $prodOrder = $this->getProdSequence($id)->getProdOrder;
        $allProdSequences = $prodOrder->getProdSequences;
        $routingId = $prodOrder->prod_routing_id;

        $totalHours = $allProdSequences->pluck('total_hours')->sum();
        $totalManHours = $allProdSequences->pluck('total_man_hours')->sum();
        $allStartDates = $allProdSequences->pluck('start_date');
        $minStartedAt = $allStartDates->min();
        $allEndDates = $allProdSequences->pluck('end_date');
        $maxFinishedAt = $allEndDates->max();
        $isFinished = $allProdSequences->pluck('status')->every(function ($value) {
            return in_array($value, ['finished', 'not_applicable', 'cancelled']);
        });

        $sheet_count = $allProdSequences->count();
        $the6Metric = $this->get6Metrics($allProdSequences, $minStartedAt, $maxFinishedAt);

        $dataUpdated = [
            'started_at' => $minStartedAt,
            'finished_at' => $maxFinishedAt,
            'total_hours' => $totalHours,
            'total_man_hours' => $totalManHours,

            'sheet_count' => $sheet_count,
        ];
        $dataUpdated += $the6Metric;
        $dataUpdated['prod_sequence_progress'] = $this->getProdSequenceProgress($allProdSequences, $routingId);
        $dataUpdated['status'] =  ($isFinished) ? 'finished' : 'in_progress';
        $prodOrder->update($dataUpdated);
    }
}
