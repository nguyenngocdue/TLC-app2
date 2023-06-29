<?php

namespace App\Http\Controllers\Api\v1\Hse;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CloneTemplateHseController extends Controller
{
    public function cloneTemplateHse(Request $request){
        if($id = $request->input('id')){
            $ownerId =CurrentUser::id();
            $params = [
                '--ownerId' => $ownerId,
                '--inspTmplId' => $id,
            ];
            $result = Artisan::call("ndc:cloneHse", $params);
            $response = ['code' => $result ? 404 : 200];
            if ($result) {
                $response['message'] = Artisan::output();
            } else {
                $idHseChklstSht = trim(Artisan::output());
                Log::info($idHseChklstSht);
                Log::info(route("hse_insp_chklst_shts.edit", $idHseChklstSht));
                $response['href'] = route("hse_insp_chklst_shts.edit", $idHseChklstSht);
                $response['message'] = "Cloned successfully.";
            }
            return $response;
        }
    }
}