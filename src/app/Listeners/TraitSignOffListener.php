<?php

namespace App\Listeners;

use Illuminate\Support\Str;

trait TraitSignOffListener
{
    private function getMeta($tableName, $signableId)
    {
        // Log::info($data);
        // $tableName = $data['tableName'];
        // $signableId = $data['signableId'];

        $modelPath = Str::modelPathFrom($tableName);
        $sheet = $modelPath::find($signableId);
        $chklst = $sheet->getChklst;
        // Log::info($chklst);
        $prodOrder = $chklst->getProdOrder;
        $subProject = $chklst->getSubProject;
        $project = $subProject->getProject;
        // Log::info($prodOrder);

        $result = [
            "projectName" => $project->name,
            "subProjectName" => $subProject->name,
            "moduleName" => $prodOrder->production_name . " (" . $prodOrder->name . ")",
            "disciplineName" => $sheet->getProdDiscipline->name,
            "checksheetName" => $sheet->name,
            'url' => route($tableName . ".edit", $signableId),
        ];
        // Log::info($result);
        return $result;
    }
}
