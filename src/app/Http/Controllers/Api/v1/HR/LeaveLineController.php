<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\BigThink\Options;
use App\Http\Controllers\Controller;
use App\Utils\Support\DateTimeConcern;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveLineController extends Controller
{
    function getRemainingDays(Request $request)
    {
        // $hr_month_starting_date = config()->get('hr.month_starting_date', 26);
        // $hr_month_ending_date = config()->get('hr.month_ending_date', 25);

        $lines = $request->input('lines');
        Log::info($lines);

        $result = [];
        foreach ($lines as $line) {
            $item['remaining_days'] = $line['user_id'];
            $result[] = $item;
        }

        return ResponseObject::responseSuccess(
            $result,
            $lines,
            "Return total days this user has applied LA in this year, only count the lines that have ID less than this LA line."
        );
    }
}
