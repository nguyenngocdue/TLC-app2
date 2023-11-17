<?php

namespace App\Listeners;

use App\Events\UpdatedEsgSheetEvent;
use App\Models\Esg_sheet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UpdatedEsgSheetListener implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(UpdatedEsgSheetEvent $event)
    {
        try {
            $id = $event->{'sheetId'};

            $sheet = Esg_sheet::find($id);
            $masterSheet = $sheet->getMasterSheet;
            $sheets = $masterSheet->getSheets;
            $sum = $sheets->pluck('total')->sum();
            $masterSheet->total = $sum;
            $masterSheet->save();


            Toastr::success("The Event Updated Time Successfully!", 'Event Update Successfully');
        } catch (\Throwable $th) {
            Toastr::error($th->getMessage(), 'Event Update Failed');
        }
    }
}
