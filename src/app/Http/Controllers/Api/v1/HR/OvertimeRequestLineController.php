<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Controller;
use App\Utils\Constant;
use App\Utils\Support\DateTimeConcern;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OvertimeRequestLineController extends Controller
{
    function getRemainingHours2(Request $request)
    {
        $hr_month_starting_date = config()->get('hr.month_starting_date', 26);
        $hr_month_ending_date = config()->get('hr.month_ending_date', 25);

        $lines = $request->input('lines');
        $result = [];
        $allowExtra = config("hr.allow_extra_ot_hour"); //$this->allowExtra();
        foreach ($lines as $line) {
            $user_id = $line['user_id'];
            $ot_date = $line['ot_date'];
            $otrl_id = $line['id'];

            $ot_date = DateTimeConcern::convertForSaving('picker_date', $ot_date);
            [$begin_date_of_month, $end_date_of_month] = DateTimeConcern::getMonthBeginAndEndDate0($ot_date);
            [$begin_date_of_year, $end_date_of_year] = DateTimeConcern::getYearBeginAndEndDate0($ot_date);
            // $idCmp = is_numeric($otrl_id) ? "AND id < $otrl_id" : ""; //<< id="to be generated" 
            $idCmp = "AND concat(ot_date, otrl.id) < concat('$ot_date',$otrl_id)";
            // dump($idCmp);

            $sql = "SELECT * FROM 
                (SELECT 
                    if(ot_date BETWEEN concat(year(ot_date),'-12-$hr_month_starting_date') AND concat(year(ot_date),'-12-31'), year(ot_date)+1, year(ot_date)) AS `year0`, 
                    user_id, 
                    round(400 - sum(total_time),2) AS `year_remaining_hours`
                FROM `hr_overtime_request_lines` otrl, `hr_overtime_requests` otr
                WHERE 1=1
                    AND user_id=$user_id
                    AND otr.id=otrl.hr_overtime_request_id
                    AND otr.deleted_at IS NULL
                    AND ot_date BETWEEN '$begin_date_of_year' AND '$end_date_of_year'
                    AND otrl.deleted_at IS NULL
                    $idCmp
                GROUP BY user_id, year0) AS year0
                LEFT JOIN
                (SELECT 
                    if(day(ot_date) BETWEEN 1 AND $hr_month_ending_date, substr(ot_date, 1,7), substr(DATE_ADD(ot_date, INTERVAL 1 MONTH),1,7)) AS `year_month0`,
                    user_id, 
                    round(40 - sum(total_time),2) AS `month_remaining_hours`
                FROM `hr_overtime_request_lines` otrl, `hr_overtime_requests` otr
                WHERE 1=1
                    AND user_id=$user_id
                    AND otr.id=otrl.hr_overtime_request_id
                    AND otr.deleted_at IS NULL
                    AND ot_date BETWEEN '$begin_date_of_month' AND '$end_date_of_month'
                    AND otrl.deleted_at IS NULL
                    $idCmp
                GROUP BY user_id, year_month0) AS month0
                ON (month0.user_id = year0.user_id)
                ";
            // Log::info($sql);
            $resultLine = DB::select($sql);
            $resultLine_0 = $resultLine[0] ?? [];

            $allowExtraHour = isset($allowExtra[$resultLine_0->year0][$resultLine_0->user_id]);
            $lineInNotEmpty = isset($resultLine_0->month_remaining_hours);
            if ($allowExtraHour && $lineInNotEmpty) {
                $resultLine_0->allowExtraMonth = $allowExtra[$resultLine_0->year0][$resultLine_0->user_id]['month'];
                $resultLine_0->month_remaining_hours += $resultLine_0->allowExtraMonth;

                $resultLine_0->allowExtraYear = $allowExtra[$resultLine_0->year0][$resultLine_0->user_id]['year'];
                $resultLine_0->year_remaining_hours += $resultLine_0->allowExtraYear;
            }
            // dump($resultLine_0);
            $result[] = $resultLine_0;
        }
        // dump($result);
        return ResponseObject::responseSuccess(
            $result,
            $lines,
            "Return total hours this user has OT in this month and this year, only count the lines that have ID less than this OT line."
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
