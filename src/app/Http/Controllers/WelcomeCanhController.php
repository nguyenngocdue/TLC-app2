<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibApps;
use App\Http\Resources\HrTsLineCollection;
use App\Models\Comment;
use App\Models\Hr_timesheet_officer;
use App\Models\Zunit_test_03;

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
            ]
        );
    }
}
