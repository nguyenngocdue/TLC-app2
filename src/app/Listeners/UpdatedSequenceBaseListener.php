<?php

namespace App\Listeners;

use App\Events\UpdatedSequenceBaseEvent;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatedSequenceBaseListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UpdatedSequenceBaseEvent  $event
     * @return void
     */
    public function handle(UpdatedSequenceBaseEvent $event)
    {
        try {
            $id = $event->{'idChildren'};
            $modelPath = $event->{'modelPathChildren'};
            $instance = $modelPath::findFromCache($id);
            $parentInstance = $instance->getProdOrder;
            $listInstanceChildren = $parentInstance->getProdSequences;
            $startedAtMin = $listInstanceChildren->pluck('start_date')->min();
            $finishedMax = $listInstanceChildren->pluck('end_date')->max();
            $dataUpdated = [
                'started_at' => $startedAtMin,
                'finished_at' => $finishedMax,
            ];
            $parentInstance->update($dataUpdated);
            Toastr::success("The Event Updated Time Successfully!", 'Event Update Successfully');
        } catch (\Throwable $th) {
            Toastr::error($th->getMessage(), 'Event Update Failed');
        }
    }
}
