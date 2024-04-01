<?php

namespace App\Http\Services;

use App\Models\Prod_sequence;

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

    protected $finishedArray = ['closed', 'finished', 'approved'];
    protected $naArray = ['not_applicable', 'cancelled'];
    private function getProdSequenceProgress($allProdSequences)
    {
        $finishedCount = 0;
        $total = 0;
        foreach ($allProdSequences as $prodSequence) {
            $status = $prodSequence->status;
            if (in_array($status, $this->finishedArray)) $finishedCount++;
            if (!in_array($status, $this->naArray)) $total++;
        }

        return round(100 * $finishedCount / $total, 2);
    }

    public function update($id)
    {
        $prodOrder = $this->getProdSequence($id)->getProdOrder;
        $allProdSequences = $prodOrder->getProdSequences;

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
        $dataUpdated['prod_sequence_progress'] = $this->getProdSequenceProgress($allProdSequences);
        $dataUpdated['status'] =  ($isFinished) ? 'finished' : 'in_progress';
        $prodOrder->update($dataUpdated);
    }
}
