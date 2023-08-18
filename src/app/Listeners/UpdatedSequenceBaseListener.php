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
            $totalHours = $listInstanceChildren->pluck('total_hours')->sum();
            $totalManHours = $listInstanceChildren->pluck('total_man_hours')->sum();
            $startedAtMin = $listInstanceChildren->pluck('start_date')->min();
            $finishedMax = $listInstanceChildren->pluck('end_date')->max();
            $isFinished = $listInstanceChildren->pluck('status')->every(function($value){
                return in_array($value,['finished','no','canceled']);
            });
            $dataUpdated = [
                'started_at' => $startedAtMin,
                'finished_at' => $finishedMax,
                'total_hours' => $totalHours,
                'total_man_hours' => $totalManHours,
            ];
            if($isFinished) $dataUpdated['status'] = 'finished';
            $parentInstance->update($dataUpdated);
            Toastr::success("The Event Updated Time Successfully!", 'Event Update Successfully');
        } catch (\Throwable $th) {
            Toastr::error($th->getMessage(), 'Event Update Failed');
        }
    }
}
