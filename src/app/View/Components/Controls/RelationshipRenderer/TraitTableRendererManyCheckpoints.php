<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use App\Models\Qaqc_insp_group;

trait TraitTableRendererManyCheckpoints
{
    private function renderManyCheckpoints($tableName, $paginatedDataSource, $lineModelPath, $columns, $editable, $instance, $isOrderable, $colName, $tableFooter)
    {
        $dataSource = $paginatedDataSource;
        $checkPointIds = $dataSource->pluck('id');

        $result = [];
        foreach ($dataSource as $checkpoint) {
            $groupId = $checkpoint->qaqc_insp_group_id;
            $result[$groupId]['items'][$checkpoint->id] = $checkpoint;
        }

        foreach ($result as $groupId => &$group) {
            $group['name'] = Qaqc_insp_group::find($groupId)->name;
        }

        return view('components.controls.many-checkpoint-params', [
            'groupedCheckpoints' => $result,
            'lineType' => $tableName,
            'checkPointIds' => $checkPointIds,
            'table01Name' => $this->table01Name,
            'readOnly' => $this->readOnly,
        ]);
    }
}
