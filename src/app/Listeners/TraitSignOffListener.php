<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitSignOffListener
{
    private function getMetaOfInspChklstSht($tableName, $signableId, $sheet)
    {
        $chklst = $sheet->getChklst;
        $prodOrder = $chklst->getProdOrder;
        $subProject = $chklst->getSubProject;
        $project = $subProject->getProject;

        return [
            "projectName" => $project->name,
            "subProjectName" => $subProject->name,
            "moduleName" => $prodOrder->production_name . " (" . $prodOrder->name . ")",
            "disciplineName" => $sheet->getProdDiscipline->name,
            "checksheetName" => $sheet->name,
            'url' => route($tableName . ".edit", $signableId),
        ];
    }

    private function getMetaOfQaqcPunchlists($tableName, $signableId, $sheet)
    {
        $chklst = $sheet->getChklst;
        $prodOrder = $chklst->getProdOrder;
        $subProject = $chklst->getSubProject;
        $project = $subProject->getProject;
        // Log::info($sheet);
        return [
            'projectName' => $project->name,
            'subProjectName' => $subProject->name,
            'moduleName' => $prodOrder->production_name . " (" . $prodOrder->name . ")",
            'disciplineName' => "QAQC",
            'checksheetName' => "Punchlist",
            'url' => route($tableName . ".edit", $signableId),
        ];
    }

    private function getMetaOfEcoSheets($tableName, $signableId, $sheet)
    {
        // Log::info($sheet);
        $project = $sheet->getProject;
        // Log::info($project);
        $subProjects = $sheet->getSubProjectsOfEco();
        // Log::info($subProjects);

        return [
            'projectName' => $project->name,
            'subProjectName' => $subProjects->pluck('name')->join(', '),
            'moduleName' => "N/A",
            'disciplineName' => "N/A",
            'checksheetName' => $sheet->name,
            'url' => route($tableName . ".edit", $signableId),
        ];
    }

    private function getMeta($tableName, $signableId)
    {
        // Log::info($data);
        // $tableName = $data['tableName'];
        // $signableId = $data['signableId'];

        $modelPath = Str::modelPathFrom($tableName);
        $sheet = $modelPath::find($signableId);

        switch ($tableName) {
            case 'insp_chklst_shts':
                return $this->getMetaOfInspChklstSht($tableName, $signableId, $sheet);
            case 'qaqc_punchlists':
                return $this->getMetaOfQaqcPunchlists($tableName, $signableId, $sheet);
            case 'eco_sheets':
                return $this->getMetaOfEcoSheets($tableName, $signableId, $sheet);
            default:
                Log::error("[$tableName] not found in TraitSignOffListener.php");
                return [];
        }
    }
}
