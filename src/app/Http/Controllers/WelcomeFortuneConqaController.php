<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\v1\qaqc\ConqaArchiveRendererController;
use App\View\Components\Renderer\ConqaArchive\ConqaArchive;
use Illuminate\Http\Request;

class WelcomeFortuneConqaController extends Controller
{
    function __construct(
        // private $exportMode = 'attachment',
        private $newLine = "\n",
        private $exportMode = 'checkpoint',
    ) {}
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

    private function loadFolder($name, $uuid)
    {
        $item = (object)[
            'name' => $name,
            'uuid' => $uuid,
        ];
        $conqaArchive = new ConqaArchive($item, true);
        $conqaArchiveRenderer = new ConqaArchiveRendererController(true);

        $lines = $this->loadTree($item, $conqaArchive);

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

                // $this->loadCheckpoint($item, $conqaArchiveRenderer, $line, $line);
            }
        }
    }

    public function index(Request $request)
    {
        $allProjects = [
            // [
            //     'name' => 'STW',
            //     'uuid' => '92f3bbe6-4d6a-4fe5-8e19-ffa38cb7539c', // Townhouse
            // ],
            // [
            //     'name' => 'GHT',
            //     'uuid' => '48e8af9f-717f-4e32-9696-cc77df35eba9', // Module
            // ],
            // [
            //     'name' => 'NGH',
            //     'uuid' => 'ec43b961-60d8-4029-8c75-29d384250359', // Module
            // ],
            // [
            //     'name' => 'BTH',
            //     'uuid' => '3108bc46-4e90-4ffc-a192-6f2407ba76dd', // Module
            // ],
            // [
            //     'name' => 'HLC_OFFSITE_N17',
            //     'uuid' => '54ba204c-584a-47a2-b9a1-5089c33b7781', // Module
            // ],
            // [
            //     'name' => 'HLC_OFFSITE_N18',
            //     'uuid' => '243064a6-c5ea-4521-8a34-876410cc2abe', // Module
            // ],
            // //SHIPPING START FROM HERE
            // [
            //     'name' => 'GHT',
            //     'uuid' => 'b3d5f8bd-ffd5-498b-beac-07e925e29e43', // Shipping
            // ],
            // [
            //     'name' => 'NGH',
            //     'uuid' => '8e8850ad-cc2d-4a67-8160-c2beef4a19d0', // Shipping
            // ],
            // [
            //     'name' => 'BTH',
            //     'uuid' => '4d382d05-579e-4a0b-8f5a-a6ccfceaf070', // Shipping
            // ],
            [
                'name' => 'HLC_SHIPPING',
                'uuid' => '08f579d5-16c3-4a1f-860a-2b9f97d3a228', // Shipping
            ],
        ];

        ob_start();
        echo "<pre>";
        echo "id,parentId,type,text,checkpointType,checkpointValue,createdBy,createdAt,attachmentCount";
        echo $this->newLine;

        foreach ($allProjects as $project) {
            [$name, $uuid] = [$project['name'], $project['uuid']];
            $this->loadFolder($name, $uuid);
        }

        echo "</pre>";
        $csvFile = ob_get_clean();
        return $csvFile;

        return view("welcome-fortune", []);
    }
}
