<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConqaArchiveRendererController extends Controller
{
    public function __construct(
        private $loadFromStorage = false
    ) {
    }

    public function getFileContent($projName, $folderUuid)
    {
        if ($this->loadFromStorage) {
            $path = storage_path("app/conqa_archive/database/{$projName}/cl/");
        } else {
            $path = env('AWS_ENDPOINT') . '/conqa-backup/database/' . $projName . "/cl/";
        }
        $content = file_get_contents($path . $folderUuid . ".json");

        return $content;
    }

    function render(Request $request)
    {
        $folderUuid = $request['folderUuid'];
        $projName = $request['projName'];
        // $folderUuid = $request['folderUuid'];
        $content = $this->getFileContent($projName, $folderUuid);

        $json = json_decode($content);

        $mediaContentTypes = [
            "image/webp",
            "image/avif",
            "image/png",
            "image/svg+xml",
            "image/jpeg",
            "video/mp4",
            "video/quicktime",
        ];
        $documentContentTypes = [
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/x-zip-compressed",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "application/pdf",
        ];

        $checkpoints = $json->checkpoints;
        $signoffs = array_filter(($json->signoffs ?? []), fn ($s) => !(isset($s->deleted) && $json->signoffs == true));

        //Attach signoffs into checkpoints
        foreach ($signoffs as $signoff) {
            $cpid = $signoff->signoffPointId;
            if (!isset($checkpoints->{$cpid}->signoffs)) $checkpoints->{$cpid}->signoffs = [];
            $checkpoints->{$cpid}->signoffs[] = $signoff;
        }

        $renderer = view("components.renderer.conqa_archive.conqa_renderer", [
            "json" => $json,
            "sections" => $json->sections,
            "checkpoints" => $checkpoints,
            "data" => $json->data,
            "attachments" => $json->attachments,
            "mediaContentTypes" => $mediaContentTypes,
            "documentContentTypes" => $documentContentTypes,

            "signoffs" => $signoffs,
            "users" => $json->users,
            "minioPath" => env('AWS_ENDPOINT') . '/conqa-backup/' . $projName,
            'isAdmin' => CurrentUser::isAdmin(),
        ]);

        return $renderer;
    }

    function renderToJson(Request $request)
    {
        return ResponseObject::responseSuccess(htmlspecialchars($this->render($request) . ""));
    }
}
