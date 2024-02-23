<?php

namespace App\Listeners;

use App\Events\UpdatedUserPositionEvent;
use App\Models\User_position;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UpdatedUserPositionListener
{
    public function __construct()
    {
        //
    }

    public function handle(UpdatedUserPositionEvent $event)
    {
        try {
            $id = $event->{'userPositionId'};

            $userPosition = User_position::find($id);
            $status = $userPosition->status;
            if($status == 'active'){
                $jobDesDraft = $userPosition->job_desc_draft;
                $jobReqDraft = $userPosition->job_requirement_draft;
                $userPosition->job_desc = $jobDesDraft;
                $userPosition->job_requirement = $jobReqDraft;
                $userPosition->save();
                Toastr::success("The Event Updated User Position Successfully!", 'Event Update Successfully');
            }
        } catch (\Throwable $th) {
            Toastr::error($th->getMessage(), 'Event Update Failed');
        }
    }
}
