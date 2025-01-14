<?php

namespace App\Http\Services;

use App\Models\Prod_routing_detail;
use App\Models\Prod_sequence;
use Illuminate\Support\Facades\Log;

class ProdSequenceToItselfService
{
    use TraitProdSequenceAdvancedMetrics;

    public function update($id)
    {
        $prodSequence = Prod_sequence::query()->with('getProdRuns')->find($id);
        $start_date = $prodSequence->start_date;
        $end_date = $prodSequence->end_date;
        $newMetrics = $this->get6Metrics([$prodSequence], $start_date, $end_date);

        $progress = 0;
        switch ($prodSequence->status) {
            case 'in_progress':
                $totalHours = $prodSequence->total_hours;

                $detail = Prod_routing_detail::query()
                    ->where('prod_routing_link_id', $prodSequence->prod_routing_link_id)
                    ->where('prod_routing_id', $prodSequence->prod_routing_id)
                    ->first();
                $avg_actual_hours = $detail->avg_actual_hours ?? -1;
                if ($totalHours > $avg_actual_hours) $totalHours = $avg_actual_hours;

                $progress = round(0.8 * $totalHours / $avg_actual_hours * 100, 3);
                // Log::info($totalHours . " / " . $avg_actual_hours);
                break;
            case 'finished':
            case 'rework':
                $progress = 100;
                break;
            default:
                $progress = 0;
                break;
        }
        // Log::info($progress);
        $newMetrics['progress'] = $progress;

        $prodSequence->update($newMetrics);
    }
}
