<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Events\RequestSignOffEvent;
use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class RequestSignOffController extends Controller
{
    public function requestSignOff(Request $request)
    {
        $cuid = CurrentUser::id();
        $uids = $request->input('uids');
        $signableId = $request->input('signableId');
        $signableType = $request->input('signableType');
        event(new RequestSignOffEvent($uids, $signableId, $signableType, $cuid));
        return ['code' => 200, 'message' => 'Request email is queued.'];
    }
}
