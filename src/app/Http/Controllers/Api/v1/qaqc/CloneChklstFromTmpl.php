<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CloneChklstFromTmpl extends Controller
{
    public function clone(Request $request)
    {
        // $ownerId = $request->input('ownerId');
        // $prodOrderId = $request->input('prodOrderId');
        // $inspTmplId = $request->input('inspTmplId');
        $params = $request->input();
        $params = [
            '--ownerId' => $request->input('ownerId'),
            '--prodOrderId' => $request->input('prodOrderId'),
            '--inspTmplId' => $request->input('inspTmplId'),
        ];
        // dump($params);

        $result = Artisan::call("ndc:createAndClone", $params);
        $message = $result ?  Artisan::output() : "Cloned successfully.";

        return [
            'code' => $result ? 404 : 200,
            'message' => $message,
        ];
    }
}
