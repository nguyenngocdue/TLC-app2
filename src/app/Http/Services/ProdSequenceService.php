<?php

namespace App\Http\Services;

class ProdSequenceService
{
    function __construct(
        protected ProdSequenceToProdOrderService $prodSequenceToProdOrderService,
        protected ProdOrderProgressService $prodOrderProgressService,
        protected ProdSequenceToProdRoutingLinkService $prodSequenceToProdRoutingLinkService,
        protected ProdSequenceToItselfService $prodSequenceToItselfService,
    ) {}

    public function update($sequenceId)
    {
        $this->prodSequenceToItselfService->update($sequenceId);
        $this->prodSequenceToProdOrderService->update($sequenceId);
        $this->prodOrderProgressService->update($sequenceId);
        $this->prodSequenceToProdRoutingLinkService->update($sequenceId);
    }
}
