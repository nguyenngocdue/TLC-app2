<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Controller;
use App\Models\Hr_leave_cat;
use App\Models\Hr_leave_tpto_line;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveLineController extends Controller
{
    function getRemainingDays(Request $request)
    {
        $lines = $request->input('lines');
        // Log::info($lines);
        $msgs = [];

        $result = [];
        foreach ($lines as $line) {
            $id = $line['id'];
            $user_id = $line['user_id'];
            $leave_cat_id = $line['leave_cat_id'];
            $leave_date = $line['leave_date'];

            $tptos = Hr_leave_tpto_line::query()
                ->where('user_id', $user_id)
                ->where('year', substr($leave_date, 0, 4))
                ->get();

            $leaveCat = Hr_leave_cat::find($leave_cat_id);
            $leaveCatKey = $leaveCat->tpto_key;
            if (!$leaveCatKey) {
                $msg = "Leave cat TPTO key not found: $leave_cat_id. ";
                // Log::info($msg);
                $msgs[] = $msg;
            }

            $tpto = (sizeof($tptos) > 0) ? $tptos[0] : null;
            if (!$tpto) {
                $msg = "TPTO not found for user $user_id and year " . substr($leave_date, 0, 4) . ". ";
                // Log::info($msg);
                $msgs[] = $msg;
                $total_allowed_days = -999;
            } else {
                $total_allowed_days = $tpto[$leaveCatKey];
            }


            $sql = "SELECT * 
            FROM hr_leave_lines
            WHERE 1=1
                AND user_id = $user_id
                AND leave_cat_id = $leave_cat_id
                AND id < $id
                AND deleted_at IS NULL
            ";
            $resultLine = DB::select($sql);
            // Log::info($resultLine);

            $used_days = 0;
            foreach ($resultLine as $line) {
                $used_days += $line->leave_days;
            }

            $item['remaining_days'] = $total_allowed_days - $used_days;
            $result[] = $item;
        }

        return ResponseObject::responseSuccess(
            $result,
            $lines,
            join($msgs) . " Return total days this user has applied LA in this year, only count the lines that have ID less than this LA line. "
        );
    }
}
