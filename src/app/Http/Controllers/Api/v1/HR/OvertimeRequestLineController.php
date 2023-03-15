<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Controller;
use App\Utils\Constant;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OvertimeRequestLineController extends Controller
{
    function getRemainingHours2(Request $request)
    {
        $user_id = $request['user_id'];
        $ot_date = $request['ot_date'];
        $otrl_id = $request['id'];
        $year_month0 = Carbon::createFromFormat(Constant::FORMAT_DATE_ASIAN, $ot_date)->format(Constant::FORMAT_YEAR_MONTH0);
        $year0 = substr($year_month0, 0, 4);

        $sql = "SELECT * FROM 
            (SELECT 
                substr(ot_date, 1, 7) AS `year_month0`, 
                user_id, 
                round(40 - sum(total_time),2) AS `month_remaining_hours`
            FROM `hr_overtime_request_lines`
            WHERE 1=1
                AND user_id=$user_id
                AND substr(ot_date, 1, 7)='$year_month0'
                AND id < $otrl_id
            GROUP BY user_id, year_month0) AS month0,
            (SELECT 
                substr(ot_date, 1, 4) AS `year0`, 
                user_id, 
                round(200 - sum(total_time),2) AS `year_remaining_hours`
            FROM `hr_overtime_request_lines`
            WHERE 1=1
                AND user_id=$user_id
                AND substr(ot_date, 1, 4)='$year0'
                AND id < $otrl_id
            GROUP BY user_id, year0) AS year0
            WHERE month0.user_id = year0.user_id
            ";
        // Log::info($sql);
        $result = DB::select($sql);
        return ResponseObject::responseSuccess(
            $result,
            ['user_id' => $user_id, 'year_month0' => $year_month0, 'otrl_id' => $otrl_id],
            "Return total hours this user has OT in this month, only count the lines that have ID less than this OT line."
        );
    }

    // function getRemainingHours(Request $request)
    // {
    //     // dump($request->input());
    //     $user_id = $request['user_id'];
    //     $ot_date = $request['ot_date'];
    //     $year_month0 = Carbon::createFromFormat(Constant::FORMAT_DATE_ASIAN, $ot_date)->format(Constant::FORMAT_YEAR_MONTH0);
    //     $result = View_otr_remaining::where("user_id", $user_id)->where("year_month0", $year_month0)->get();
    //     return ResponseObject::responseSuccess(
    //         $result,
    //         ['user_id' => $user_id, 'year_month0' => $year_month0],
    //         "Return total hours this user has OT in this month"
    //     );
    // }
}
