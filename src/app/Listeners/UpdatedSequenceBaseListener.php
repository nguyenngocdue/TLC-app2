<?php

namespace App\Listeners;

use App\Events\UpdatedSequenceBaseEvent;
use App\Http\Services\ProdSequenceService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;

class UpdatedSequenceBaseListener
{
    public function __construct(
        protected ProdSequenceService $prodSequenceService,
    ) {
        //
    }

    public function handle(UpdatedSequenceBaseEvent $event)
    {
        try {
            $id = $event->{'idChildren'};
            $this->prodSequenceService->update($id);

            Toastr::success("The Event Updated Time Successfully!", 'Event Update Successfully');
        } catch (\Throwable $th) {
            Toastr::error($th->getMessage(), 'Event Update Failed');
        }
    }
}
