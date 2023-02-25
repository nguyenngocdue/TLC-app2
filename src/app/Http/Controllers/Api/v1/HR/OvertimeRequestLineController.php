<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Controller;
use App\Models\View_otr_remaining;
use App\Utils\System\Api\ResponseObject;

class OvertimeRequestLineController extends Controller
{
    function getRemainingHours($user_id, $year_month0)
    {
        $result = View_otr_remaining::where("user_id", $user_id)->where("year_month0", $year_month0)->get();
        return ResponseObject::responseSuccess(
            $result,
            ['user_id' => $user_id, 'year_month0' => $year_month0],
            "Hello getRemainingHours"
        );
    }
}
