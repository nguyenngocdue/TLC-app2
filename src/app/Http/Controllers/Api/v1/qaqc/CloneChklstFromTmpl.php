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
        $response = ['code' => $result ? 404 : 200];
        if ($result) {
            $response['message'] = Artisan::output();
        } else {
            $id = trim(Artisan::output());
            // $response['insertedId'] = $id;
            $response['href'] = route("qaqc_insp_chklsts.edit", $id);
            $response['message'] = "Cloned successfully.";
        }

        return $response;
    }
}
