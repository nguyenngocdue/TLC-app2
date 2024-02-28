<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\v1\qaqc\ConqaArchiveRendererController;
use App\View\Components\Renderer\ConqaArchive\ConqaArchive;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    function __construct(
        private $exportMode = 'attachment',
        private $newLine = "\n",
        // private $exportMode = 'checkpoint',
    ) {
    }
    public function getType()
    {
        return "dashboard";
    }

    private function loadTree($item, $conqaArchive)
    {
        $path = storage_path("app/conqa_archive/database/{$item->name}/");
        // $path = env('AWS_ENDPOINT') . '/conqa-backup/database/' . $item->name . "/";
        $treeArray = $conqaArchive->loadTree($path, $item->uuid, "#");
        // dump($treeArray);

        $lines = [];
        foreach ($treeArray as $treeNode) {
            $item = [
                'id' => $treeNode->id,
                'parent' => $treeNode->parent,
                'type' => $treeNode->type,
                'text' => '"' . $treeNode->text . '"',
            ];
            $lines[] = $item;
        }

        return $lines;
    }

    private function loadCheckpoint($item, $conqaArchiveRenderer, $checksheet, $parentLine)
    {
        // $path = storage_path("app/conqa_archive/database/{$item->name}/cl/");
        // $path = env('AWS_ENDPOINT') . '/conqa-backup/database/' . $item->name . "/";
        $content = $conqaArchiveRenderer->getFileContent($item->name, $checksheet['id']);
        $json = json_decode($content);
        $users = $json->users;
        // unset($parentLine['parent']);
        // dump($parentLine);
        // $parentLine = join(",", $parentLine);

        $sections = $json->sections;
        foreach ($sections as $section) {
            $sectionId = $section->sectionId;
            $sectionName = $section->name;
            if ($this->exportMode == 'checkpoint') {
                echo $sectionId . "," . join(",", [$parentLine['id'], "section"]) . "," . $sectionName;
                echo  $this->newLine;
            }

            foreach ($section->checkpoints as $checkpointId) {
                $checkpoint = $json->checkpoints->{$checkpointId};
                $lineItem = [
                    // $sectionStr,
                    $checkpointId,
                    $sectionId,
                    "checkpoint",
                    '"' . $checkpoint->name . '"',
                    $checkpoint->type,
                ];

                if ($checkpoint->type == 'pass-fail') {
                    $checkpointData = $json->data->checkpoints->{$checkpointId};
                    // dd($checkpointData);
                    $updatedBy = "NULL";
                    if (isset($checkpointData->valueUpdatedBy)) {
                        $updatedBy = $users->{$checkpointData->valueUpdatedBy}->name;
                    }
                    $updatedAt = "NULL";
                    if (isset($checkpointData->valueUpdatedAt)) {
                        $updatedAt = substr($checkpointData->valueUpdatedAt, 0, 19);
                        $updatedAt[10] = " ";
                    }
                    $lineItem[] = $checkpointData->value;
                    $lineItem[] = $updatedBy;
                    $lineItem[] = $updatedAt;
                    $lineItem[] = sizeof($checkpointData->attachments);

                    if ($this->exportMode == 'attachment') {
                        $itemStr = join(",", $lineItem);
                        if (sizeof($checkpointData->attachments) > 0) {
                            foreach ($checkpointData->attachments as $attachmentId) {
                                $attachment = $json->attachments->{$attachmentId};
                                if (isset($attachment->deleted)) {
                                    continue;
                                }
                                $attachmentType = substr($attachmentId, 0, strpos($attachmentId, ":", 0));
                                $fileUuid = substr($attachmentId, strlen($attachmentType) + 1);
                                // if ($attachmentType != 'cmt') continue;
                                // dump($attachment);

                                $createdBy = $users->{$attachment->createdBy}->name;
                                $createdAt = substr($attachment->createdAt, 0, 19);
                                $createdAt[10] = " ";

                                $fileName = isset($attachment->fileName) ? '"' . $attachment->fileName . '"' : "NO_FILENAME";

                                if ($attachmentType == "file") {
                                    $lineItem = [
                                        $itemStr,
                                        $attachmentType,
                                        $attachmentId,
                                        $fileUuid,
                                        $fileName,
                                        $attachment->contentType ?? "NO_CONTENTTYPE",
                                        $createdBy,
                                        $createdAt,
                                        $attachment->fileId ?? "NO_FILEID",
                                    ];
                                }
                                if ($attachmentType == "cmt") {
                                    $comment = $attachment->text;
                                    $lineItem = [
                                        $itemStr,
                                        $attachmentType,
                                        $attachmentId,
                                        $fileUuid,
                                        $fileName,
                                        $attachment->contentType ?? "NO_CONTENTTYPE",
                                        $createdBy,
                                        $createdAt,
                                        '"' . $comment . '"',
                                    ];
                                }
                                if ($attachmentType == 'sig') {
                                    $path = storage_path("app/conqa_archive/database_attachments/{$item->name}/sig/");
                                    $file = $path . $fileUuid . ".svg";
                                    $content = file_get_contents($file);

                                    // $pattern = '/<svg[^>]*>(.*?)<\/svg>/s'; // Regular expression pattern
                                    $pattern = '/(<svg[^>]*>.*?<\/svg>)/s';
                                    preg_match($pattern, $content, $matches); // Perform the regex match

                                    $lineItem = [
                                        $itemStr,
                                        $attachmentType,
                                        $attachmentId,
                                        $fileUuid,
                                        $fileName,
                                        $attachment->contentType ?? "NO_CONTENTTYPE",
                                        $createdBy,
                                        $createdAt,
                                        // "'" . $content . "'",
                                        // '"' . $content . '"',
                                        $matches[1],
                                    ];
                                }
                                echo join(",", $lineItem);
                                echo $this->newLine;
                            }
                        }
                    }
                }

                if ($this->exportMode == 'checkpoint') {
                    echo join(",", $lineItem);
                    echo $this->newLine;
                }
            }
        }
    }

    public function index(Request $request)
    {
        $item = (object)[
            'name' => 'STW',
            'uuid' => '1e125419-46e3-4814-b403-8e80ea745980', // substructure: corner block
            // 'uuid' => '92f3bbe6-4d6a-4fe5-8e19-ffa38cb7539c', // Townhouse
            // 'uuid' => '02ac71d7-b02b-4462-8fb7-e234d04bd1bb', // Offsite
            // 'uuid' => 'cc0f0c88-17b5-44c1-aa6c-bd9b421177a9', // STW
        ];
        $conqaArchive = new ConqaArchive($item, true);
        $conqaArchiveRenderer = new ConqaArchiveRendererController(true);

        $lines = $this->loadTree($item, $conqaArchive);

        ob_start();
        echo "<pre>";
        echo "id,parentId,type,text,checkpointType,checkpointValue,createdBy,createdAt,attachmentCount";
        echo $this->newLine;

        foreach ($lines as $line) {
            if ($this->exportMode == 'checkpoint') {
                if ($line['type'] == 'folder') {
                    echo join(",", $line);
                    echo $this->newLine;
                }
            }
            if ($line['type'] == 'checklist') {
                if ($this->exportMode == 'checkpoint') {
                    $parentLine = join(",", $line);
                    echo $parentLine;
                    echo $this->newLine;
                }

                $this->loadCheckpoint($item, $conqaArchiveRenderer, $line, $line);
            }
        }

        echo "</pre>";
        $csvFile = ob_get_clean();
        return $csvFile;

        return view("welcome-fortune", []);
    }
}
