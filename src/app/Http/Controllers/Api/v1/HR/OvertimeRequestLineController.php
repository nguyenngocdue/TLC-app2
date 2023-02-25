<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Controller;
use App\Models\View_otr_remaining;
use App\Utils\Constant;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OvertimeRequestLineController extends Controller
{
    function getRemainingHours(Request $request)
    {
        // dump($request->input());
        $user_id = $request['user_id'];
        $ot_date = $request['ot_date'];
        $year_month0 = Carbon::createFromFormat(Constant::FORMAT_DATE_ASIAN, $ot_date)->format(Constant::FORMAT_YEAR_MONTH0);
        $result = View_otr_remaining::where("user_id", $user_id)->where("year_month0", $year_month0)->get();
        return ResponseObject::responseSuccess(
            $result,
            ['user_id' => $user_id, 'year_month0' => $year_month0],
            "Hello getRemainingHours"
        );
    }
}
