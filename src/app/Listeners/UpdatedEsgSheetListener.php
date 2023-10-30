<?php

namespace App\Listeners;

use App\Events\UpdatedEsgSheetEvent;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;

class UpdatedEsgSheetListener
{
    public function __construct()
    {
        //
        Log::info("Listened");
    }

    public function handle(UpdatedEsgSheetEvent $event)
    {
        try {
            Log::info($event);
            $id = $event->{'sheetId'};
            Log::info("Updated " . $id);

            Toastr::success("The Event Updated Time Successfully!", 'Event Update Successfully');
        } catch (\Throwable $th) {
            Toastr::error($th->getMessage(), 'Event Update Failed');
        }
    }
}
