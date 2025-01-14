<?php

namespace App\Http\Services;

use App\Models\Prod_sequence;

class ProdSequenceToProdOrderService
{
    use TraitProdSequenceAdvancedMetrics;

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

    public function update($sequenceId)
    {
        $prodOrder = $this->getProdSequence($sequenceId)->getProdOrder;
        $allProdSequences = $prodOrder->getProdSequences;
        // $routingId = $prodOrder->prod_routing_id;

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
        // $dataUpdated['prod_sequence_progress'] = $this->getProdSequenceProgress($allProdSequences, $routingId);
        $dataUpdated['status'] =  ($isFinished) ? 'finished' : 'in_progress';
        $prodOrder->update($dataUpdated);
    }
}
