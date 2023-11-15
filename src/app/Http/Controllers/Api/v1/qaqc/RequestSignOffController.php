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
        $tableName = $request->input('tableName');
        $category = $request->input('category');
        event(new RequestSignOffEvent($uids, $signableId, $tableName, $cuid, $category));
        // return ['code' => 200, 'message' => 'Request email is queued.'];
    }
}
