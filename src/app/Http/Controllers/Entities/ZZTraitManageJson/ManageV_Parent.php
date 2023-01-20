<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\Props;
use App\Utils\Support\Json\VisibleProps;
use Illuminate\Support\Facades\Blade;
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
        $isNotVisibleProps = $this->jsonGetSet !== VisibleProps::class;
        $allStatuses = array_keys(LibStatuses::getFor($this->type));
        $allStatusesStr = "[" . join(", ", array_map(fn ($i) => '"' . $i . '"', $allStatuses)) . "]";
        echo "<script>const statuses = $allStatusesStr; const k_checked = {}; const k_current = {};</script>";
        // dump($allStatuses);
        if ($isNotVisibleProps) {
            $visibleProps = VisibleProps::getAllOf($this->type);
            // dump($visibleProps);
        }

        $result = [];
        $index = 0;
        foreach ($allProps as $prop) {
            $name = $prop['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = [
                    'name' => $name,
                    'column_name' => $prop['column_name'],
                ];
            }
            $newItem['toggle'] = Blade::render("<x-renderer.button htmlType='button' size='xs' onClick='toggleVParent($index)'>Toggle</x-renderer.button>");
            $newItem['label'] =  $prop['label'];

            if ($isNotVisibleProps) {
                foreach ($allStatuses as $status) {
                    $visible =  (isset($visibleProps[$name]) && isset($visibleProps[$name][$status]) && $visibleProps[$name][$status] === 'true');
                    if (!$visible) $newItem[$status] = "invisible";
                }
            }

            $result[] = $newItem;
            $index++;
        }
        return $result;
    }
}
