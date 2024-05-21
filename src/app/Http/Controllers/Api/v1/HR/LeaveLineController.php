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
        // $result = [];

        // $standard_month_hours = config("hr.standard_ot_hour_per_month");
        // $standard_year_hours = config("hr.standard_ot_hour_per_year");

        // foreach ($lines as $line) {
        //     $user_id = $line['user_id'];
        //     $ot_date = $line['ot_date'];
        //     $otrl_id = $line['id'];

        //     $ot_date = DateTimeConcern::convertForSaving('picker_date', $ot_date);
        //     [$begin_date_of_month, $end_date_of_month] = DateTimeConcern::getMonthBeginAndEndDate0($ot_date);
        //     [$begin_date_of_year, $end_date_of_year] = DateTimeConcern::getYearBeginAndEndDate0($ot_date);
        //     // $idCmp = is_numeric($otrl_id) ? "AND id < $otrl_id" : ""; //<< id="to be generated" 
        //     $idCmp = "AND concat(ot_date, otrl.id) < concat('$ot_date',$otrl_id)";
        //     // dump($idCmp);

        //     $sql = "SELECT * FROM 
        //         (SELECT $user_id as uid) AS uid
        //         LEFT JOIN
        //         (SELECT 
        //             if(ot_date BETWEEN concat(year(ot_date),'-12-$hr_month_starting_date') AND concat(year(ot_date),'-12-31'), year(ot_date)+1, year(ot_date)) AS `year0`, 
        //             user_id, 
        //             round($standard_year_hours - sum(total_time),2) AS `year_remaining_hours`
        //         FROM `hr_overtime_request_lines` otrl, `hr_overtime_requests` otr
        //         WHERE 1=1
        //             AND user_id=$user_id
        //             AND otr.id=otrl.hr_overtime_request_id
        //             AND otr.deleted_at IS NULL
        //             AND ot_date BETWEEN '$begin_date_of_year' AND '$end_date_of_year'
        //             AND otrl.deleted_at IS NULL
        //             $idCmp
        //         GROUP BY user_id, year0) AS year0
        //         ON (uid.uid = user_id)
        //         LEFT JOIN
        //         (SELECT 
        //             if(day(ot_date) BETWEEN 1 AND $hr_month_ending_date, substr(ot_date, 1,7), substr(DATE_ADD(ot_date, INTERVAL 1 MONTH),1,7)) AS `year_month0`,
        //             user_id, 
        //             round($standard_month_hours - sum(total_time),2) AS `month_remaining_hours`
        //         FROM `hr_overtime_request_lines` otrl, `hr_overtime_requests` otr
        //         WHERE 1=1
        //             AND user_id=$user_id
        //             AND otr.id=otrl.hr_overtime_request_id
        //             AND otr.deleted_at IS NULL
        //             AND ot_date BETWEEN '$begin_date_of_month' AND '$end_date_of_month'
        //             AND otrl.deleted_at IS NULL
        //             $idCmp
        //         GROUP BY user_id, year_month0) AS month0
        //         ON (month0.user_id = year0.user_id)
        //         ";
        //     // Log::info($sql);
        //     $resultLine = DB::select($sql);
        //     $resultLine_0 = $resultLine[0]; // ?? [];

        //     if (!isset($resultLine_0->user_id)) {
        //         $resultLine_0->user_id = 1 * $user_id;

        //         $resultLine_0->year0 = 1 * substr($ot_date, 0, 4);
        //         $resultLine_0->year_remaining_hours = $standard_year_hours;

        //         $resultLine_0->year_month0 = substr($ot_date, 0, 7);
        //         $resultLine_0->month_remaining_hours = $standard_month_hours;
        //     }

        //     if (isset($resultLine_0->year0)) {
        //         $this->addExtraOtHour($resultLine_0);
        //     }
        //     // dump($resultLine_0);
        //     $result[] = $resultLine_0;
        // }
        // dump($result);
        return ResponseObject::responseSuccess(
            [],
            [],
            "Return total days this user has applied LA in this year, only count the lines that have ID less than this LA line."
        );
    }
}
