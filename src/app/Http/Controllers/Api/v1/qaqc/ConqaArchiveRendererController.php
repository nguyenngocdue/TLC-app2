<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConqaArchiveRendererController extends Controller
{
    function getFileContent($projName, $id)
    {
        $path = storage_path("app/conqa_archive/database/{$projName}/cl/");
        $content = file_get_contents($path . $id . ".json");
        return $content;
    }

    function render(Request $request)
    {
        $id = $request['id'];
        $projName = $request['projName'];
        $content = $this->getFileContent($projName, $id);

        $json = json_decode($content);

        $renderer = view("components.renderer.conqa_archive.conqa_renderer", [
            "json" => $json,
            "sections" => $json->sections,
            "checkpoints" => $json->checkpoints,
            "data" => $json->data,
            "attachments" => $json->attachments,
            "signoffs" => $json->signoffs,
            "users" => $json->users,
            "minioPath" => env('AWS_ENDPOINT') . '/conqa-backup/BTH/file',
        ]);
        // Log::info($renderer);
        return ResponseObject::responseSuccess(htmlspecialchars($renderer . ""));
    }
}
