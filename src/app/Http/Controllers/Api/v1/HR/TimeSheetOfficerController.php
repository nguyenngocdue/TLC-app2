<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Resources\HrTsLineCollection;
use App\Models\Hr_timesheet_officer;
use Illuminate\Http\Request;

class TimeSheetOfficerController extends TimesheetController
{
    public function show(Request $request, $id)
    {
        $hrTsLines = Hr_timesheet_officer::findOrFail($id)->getHrTsLines;
        return new HrTsLineCollection($hrTsLines);
    }
}
