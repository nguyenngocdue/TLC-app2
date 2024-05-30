<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskManagerTreeExplorerRendererController extends Controller
{
    function render(Request $request)
    {
        $disciplineId = $request->input('disciplineId');
        $renderer = view("components.renderer.view-all-tree-explorer.task-manager-renderer", [
            'disciplineId' => $disciplineId,
            'user' => CurrentUser::get(),
        ]);
        return $renderer;
    }

    function renderToJson(Request $request)
    {
        return ResponseObject::responseSuccess(htmlspecialchars($this->render($request) . ""));
    }
}
