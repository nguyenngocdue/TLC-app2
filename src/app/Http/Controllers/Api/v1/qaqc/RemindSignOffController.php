<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Events\BroadcastEvents\BroadcastRemindSignOffEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RemindSignOffController extends Controller
{
    public function remind(Request $request)
    {
        $ids = $request->input('uids');
        $doc = $request->input('doc');
        // dump($doc);
        event(new BroadcastRemindSignOffEvent($ids, $doc));
        return ['code' => 200, 'message' => 'Request emails have been sent successfully.'];
    }
}
