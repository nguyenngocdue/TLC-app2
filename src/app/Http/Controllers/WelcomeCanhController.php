<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibApps;
use App\Http\Resources\HrTsLineCollection;
use App\Models\Comment;
use App\Models\Hr_timesheet_officer;
use App\Models\Hr_timesheet_worker;
use App\Models\Zunit_test_03;
use App\Utils\Support\DateTimeConcern;

class WelcomeCanhController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }
    public function index()
    {
        // dump(ini_get("curl.cainfo"));
        // dump(Storage::disk('s3')->put('dinhcanh.txt', 'NgoDinhCanh', 'public'));
        return view(
            'welcome-canh',
            [
                'timesheetableType' => Hr_timesheet_officer::class,
                'timesheetableId' => 1,
                'apiUrl' => 'https://dev2.tlcmodular.com/api/v1/hr/timesheet_staff'
            ]
            // [
            //     'timesheetableType' => Hr_timesheet_worker::class,
            //     'timesheetableId' => 1,
            //     'apiUrl' => 'https://dev2.tlcmodular.com/api/v1/hr/timesheet_worker',
            // ]
        );
    }
}
