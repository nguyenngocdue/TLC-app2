<?php

namespace App\Http\Services;

use App\Models\Prod_sequence;

class ProdSequenceToItselfService
{
    use TraitProdSequenceAdvancedMetrics;

    public function update($id)
    {
        $prodSequence = Prod_sequence::query()->with('getProdRuns')->find($id);
        $start_date = $prodSequence->start_date;
        $end_date = $prodSequence->end_date;
        $newMetrics = $this->get6Metrics([$prodSequence], $start_date, $end_date);

        $prodSequence->update($newMetrics);
    }
}
