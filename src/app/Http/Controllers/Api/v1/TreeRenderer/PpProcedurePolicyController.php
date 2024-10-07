<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Http\Controllers\Controller;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpProcedurePolicyController extends Controller
{
    function render(Request $request)
    {
        return "some info from api";
    }

    function renderToJson(Request $request)
    {
        return ResponseObject::responseSuccess(htmlspecialchars($this->render($request) . ""));
    }
}
