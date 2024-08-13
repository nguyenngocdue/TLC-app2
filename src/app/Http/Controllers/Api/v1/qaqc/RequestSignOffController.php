<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Events\InspectionSignoff\SignOffRecallEvent;
use App\Events\InspectionSignoff\SignOffRequestEvent;
use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class RequestSignOffController extends Controller
{
    public function requestSignOff(Request $request)
    {
        $cuid = CurrentUser::id();
        $request->merge(['requesterId' => $cuid]);
        event(new SignOffRequestEvent($request->input()));
        // return ['code' => 200, 'message' => 'Request email is queued.'];
    }

    public function recallSignOff(Request $request)
    {
        $cuid = CurrentUser::id();
        $request->merge(['requesterId' => $cuid]);
        event(new SignOffRecallEvent($request->input()));
        // return ['code' => 200, 'message' => 'Request email is queued.'];
    }
}
