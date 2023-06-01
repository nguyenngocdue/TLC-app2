<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Controller;
use App\Http\Resources\HrTsLineCollection;
use App\Models\Hr_timesheet_line;
use App\Models\Hr_timesheet_officer;
use App\Utils\Constant;
use App\Utils\Support\DateTimeConcern;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TimeSheetStaffController extends Controller
{
    public function index(Request $request, $id)
    {
        $hrTsLines = Hr_timesheet_officer::findOrFail($id)->getHrTsLines;
        return new HrTsLineCollection($hrTsLines);
    }
}
