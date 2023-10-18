<?php

namespace App\Http\Services;

class ProdSequenceService
{
    function __construct(
        protected ProdSequenceToProdOrderService $prodSequenceToProdOrderService,
        protected ProdSequenceToProdRoutingLinkService $prodSequenceToProdRoutingLinkService,
    ) {
    }

    public function update($id)
    {
        $this->prodSequenceToProdOrderService->update($id);
        $this->prodSequenceToProdRoutingLinkService->update($id);
    }
}
