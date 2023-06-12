<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Resources\HrTsLineCollection;
use App\Models\Hr_timesheet_worker;
use Illuminate\Http\Request;

class TimeSheetWorkerController extends TimesheetController
{
    protected $type = 'hr_timesheet_workers';
    protected $model = Hr_timesheet_worker::class;
    public function show(Request $request, $id)
    {
        $hrTsLines =  Hr_timesheet_worker::findOrFail($id)->getHrTsLines;
        return new HrTsLineCollection($hrTsLines);
    }
}
