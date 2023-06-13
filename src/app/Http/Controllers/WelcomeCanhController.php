<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibApps;
use App\Http\Resources\HrTsLineCollection;
use App\Models\Comment;
use App\Models\Hr_timesheet_officer;
use App\Models\Hr_timesheet_worker;
use App\Models\Zunit_test_03;
use App\Utils\Support\DateTimeConcern;
use Illuminate\Http\Request;

class WelcomeCanhController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {
        // dump(ini_get("curl.cainfo"));
        // dump(Storage::disk('s3')->put('dinhcanh.txt', 'NgoDinhCanh', 'public'));
        $timesheetableType = $request->input('timesheetableType');
        $timesheetableId = $request->input('timesheetableId');
        return view(
            'welcome-canh',
            [
                'timesheetableType' => $timesheetableType,
                'timesheetableId' => $timesheetableId,
                'apiUrl' => 'https://127.0.0.1:38002/api/v1/hr/timesheet_officers',
            ]
        );
    }
    public function indexAll()
    {
        return view(
            'welcome-canh-all',
        );
    }
}
