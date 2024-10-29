<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use App\Http\Services\LoadManyCheckpointService;
use Illuminate\Support\Facades\Log;

trait TraitTableRendererManyCheckpoints2
{
    private function renderManyCheckpoints2($tableName, $paginatedDataSource, $lineModelPath, $columns, $editable, $instance, $isOrderable, $colName, $tableFooter)
    {
        // dump($columns, $editable, $instance, $isOrderable, $colName, $tableFooter);
        $loadDataService = new LoadManyCheckpointService();
        [
            'dataSource' => $dataSource,
            'checkPointIds' => $checkPointIds,
            'checkPoints' => $checkPoints,
            'groupedCheckpoints' => $groupedCheckpoints,
            'categoryName' => $categoryName,
        ] = $loadDataService->getCheckpointDataSource($paginatedDataSource, $lineModelPath);

        $checkPointRadioIds = $checkPoints->filter(function ($item) {
            return $item->control_type_id == 4;
        })->pluck('id');

        $progressData = [
            ['id' => config("insp_chklst.pass"), 'color' => 'green', 'percent' => '-1%',],
            ['id' => config("insp_chklst.fail"), 'color' => 'pink', 'percent' => '-1%',],
            ['id' => config("insp_chklst.na"), 'color' => 'gray', 'percent' => '-1%',],
            ['id' => config("insp_chklst.on_hold"), 'color' => 'orange', 'percent' => '-1%',],
        ];

        // return "Rendered Many Checkpoints 2";
        return view('components.controls.many-checkpoint-params2', [
            'groupedCheckpoints' => $groupedCheckpoints,
            'lineType' => $tableName,
            'checkPointIds' => $checkPointIds,
            'table01Name' => $this->table01Name,
            'readOnly' => $this->readOnly,
            'oriCheckPoints' => $dataSource, //This to construct image gallery and progress bar
            'type' => $this->type,
            'categoryName' => $categoryName,
            'progressData' => $progressData,
            'checkPointRadioIds' => $checkPointRadioIds,
        ]);
    }
}
