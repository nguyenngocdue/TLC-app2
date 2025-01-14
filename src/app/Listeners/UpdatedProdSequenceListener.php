<?php

namespace App\Listeners;

use App\Events\UpdatedProdSequenceEvent;
use App\Http\Services\ProdSequenceService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UpdatedProdSequenceListener //implements ShouldQueue
{
    public function __construct(
        protected ProdSequenceService $prodSequenceService,
    ) {
        //
    }

    public function handle(UpdatedProdSequenceEvent $event)
    {
        try {
            $id = $event->{'idChildren'};
            $this->prodSequenceService->update($id);

            toastr()->success("The Event Updated Time Successfully!", 'Event Update Successfully');
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage(), 'Event Update Failed');
        }
    }
}
