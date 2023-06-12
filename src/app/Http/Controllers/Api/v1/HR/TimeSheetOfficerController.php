<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Resources\HrTsLineCollection;
use App\Models\Hr_timesheet_officer;
use Illuminate\Http\Request;

class TimeSheetOfficerController extends TimesheetController
{
    protected $type = 'hr_timesheet_officers';
    protected $model = Hr_timesheet_officer::class;
    public function show(Request $request, $id)
    {
        $hrTimesheetOfficer = Hr_timesheet_officer::findOrFail($id);
        $hrTsLines = $hrTimesheetOfficer->getHrTsLines;
        $initialDate = $hrTimesheetOfficer->week;
        return ['hits' => new HrTsLineCollection($hrTsLines), 'meta' => $initialDate];
    }
}
