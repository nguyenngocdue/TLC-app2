<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\Props;
use Illuminate\Support\Facades\Log;

class ManageV_Parent extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-v-parent";
    protected $excludedColumnsFromStoring = ['label', 'toggle'];

    protected function getColumns()
    {
        $firstColumns = [
            [
                "dataIndex" => 'name',
                'renderer' => 'read-only-text',
                'editable' => true,
            ],
            [
                "dataIndex" => 'column_name',
                'renderer' => 'read-only-text',
                'editable' => true,
            ],
            [
                "dataIndex" => 'label',
                'renderer' => 'read-only-text',
                'editable' => true,
            ],
            [
                "dataIndex" => 'toggle',
                'renderer' => 'read-only-text',
                'editable' => true,
                'width' => 10,
            ],
        ];

        $allStatuses = LibStatuses::getFor($this->type);
        $columns = array_map(fn ($i) => [
            'dataIndex' => $i['name'],
            'renderer' => 'checkbox',
            'editable' => true,
            'align' => 'center',
            'width' => 10,
            'title' => $i['title'],
        ], $allStatuses);
        return array_merge($firstColumns, $columns);
    }

    protected function getDataSource()
    {
        $allProps = Props::getAllOf($this->type);
        $dataInJson = $this->jsonGetSet::getAllOf($this->type);
        // dump($dataInJson);
        $result = [];
        foreach ($allProps as $prop) {
            $name = $prop['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = [
                    'name' => $name,
                    'label' => $prop['label'],
                    'column_name' => $prop['column_name'],
                ];
            }
            $newItem['toggle'] = 'button here';
            $result[] = $newItem;
        }
        return $result;
    }
}
