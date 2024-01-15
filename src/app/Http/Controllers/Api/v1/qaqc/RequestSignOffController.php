<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Events\RecallSignOffEvent;
use App\Events\RequestSignOffEvent;
use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class RequestSignOffController extends Controller
{
    public function requestSignOff(Request $request)
    {
        $cuid = CurrentUser::id();
        $request->merge(['requesterId' => $cuid]);
        event(new RequestSignOffEvent($request->input()));
        // return ['code' => 200, 'message' => 'Request email is queued.'];
    }

    public function recallSignOff(Request $request)
    {
        $cuid = CurrentUser::id();
        $request->merge(['requesterId' => $cuid]);
        event(new RecallSignOffEvent($request->input()));
        // return ['code' => 200, 'message' => 'Request email is queued.'];
    }
}
