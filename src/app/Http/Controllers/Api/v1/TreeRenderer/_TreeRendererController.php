<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Http\Controllers\Controller;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;

abstract class _TreeRendererController extends Controller
{
    abstract protected function render(Request $request);

    function renderToJson(Request $request)
    {
        return ResponseObject::responseSuccess(htmlspecialchars($this->render($request) . ""));
    }
}
