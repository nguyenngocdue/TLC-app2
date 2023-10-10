<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CloneTemplateForQaqcChecklistController extends Controller
{
    public function clone(Request $request)
    {
        dump("OBSOLETE");
    }
    // public function clone(Request $request)
    // {
    //     Log::info($request);
    //     // $ownerId = $request->input('ownerId');
    //     // $prodOrderId = $request->input('prodOrderId');
    //     // $inspTmplId = $request->input('inspTmplId');
    //     $params = $request->input();
    //     $params = [
    //         '--ownerId' => $request->input('ownerId'),
    //         '--prodOrderId' => $request->input('prodOrderId'),
    //         '--inspTmplId' => $request->input('inspTmplId'),
    //     ];
    //     // dump($params);

    //     $result = Artisan::call("ndc:cloneQaqc", $params);
    //     $response = ['code' => $result ? 404 : 200];
    //     if ($result) {
    //         $response['message'] = Artisan::output();
    //     } else {
    //         $id = trim(Artisan::output());
    //         $response['href'] = route("qaqc_insp_chklsts.edit", $id);
    //         $response['message'] = "Cloned successfully.";
    //     }

    //     return $response;
    // }
}
