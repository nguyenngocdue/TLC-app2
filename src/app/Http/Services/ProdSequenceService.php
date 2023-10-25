<?php

namespace App\Http\Services;

class ProdSequenceService
{
    function __construct(
        protected ProdSequenceToProdOrderService $prodSequenceToProdOrderService,
        protected ProdSequenceToProdRoutingLinkService $prodSequenceToProdRoutingLinkService,
        protected ProdSequenceToItselfService $prodSequenceToItselfService,
    ) {
    }

    public function update($id)
    {
        $this->prodSequenceToItselfService->update($id);
        $this->prodSequenceToProdOrderService->update($id);
        $this->prodSequenceToProdRoutingLinkService->update($id);
    }
}
