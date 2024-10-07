<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpProcedurePolicyTreeRendererController extends _TreeRendererController
{
    function render(Request $request)
    {
        return "some info from api";
    }
}
