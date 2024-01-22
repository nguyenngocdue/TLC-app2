<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use App\Models\Hse_insp_group;
use App\Models\Qaqc_insp_group;

trait TraitTableRendererManyCheckpoints
{
    private function getIdColumnAndClass()
    {
        switch ($this->type) {
            case "qaqc_insp_chklst_shts":
                return ["qaqc_insp_group_id", Qaqc_insp_group::class];
            case "hse_insp_chklst_shts":
                return ["hse_insp_group_id", Hse_insp_group::class];
            default:
                dd("Unknown how to get IdColumn and Class of [$this->type]");
        }
    }

    private function getGroupedCheckpoints($dataSource)
    {
        [$idCol, $className] = $this->getIdColumnAndClass();
        $result = [];
        foreach ($dataSource as $checkpoint) {
            $groupId = $checkpoint->{$idCol};
            $result[$groupId]['items'][$checkpoint->id] = $checkpoint;
        }

        foreach ($result as $groupId => &$group) {
            $group['name'] = $className::find($groupId)->name;
        }

        return $result;
    }

    private function renderManyCheckpoints($tableName, $paginatedDataSource, $lineModelPath, $columns, $editable, $instance, $isOrderable, $colName, $tableFooter)
    {
        $dataSource = $paginatedDataSource;
        $checkPointIds = $dataSource->pluck('id');

        $result = $this->getGroupedCheckpoints($dataSource);

        return view('components.controls.many-checkpoint-params', [
            'groupedCheckpoints' => $result,
            'lineType' => $tableName,
            'checkPointIds' => $checkPointIds,
            'table01Name' => $this->table01Name,
            'readOnly' => $this->readOnly,
            'oriCheckPoints' => $dataSource,
            'type' => $this->type,
            'item' => $this->item,
        ]);
    }
}
