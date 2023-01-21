<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibRoleSets;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\Capabilities;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ManageCapabilities extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-capability";
    protected $routeKey = "_cpb";
    protected $jsonGetSet = Capabilities::class;

    protected function getColumns()
    {
        $firstColumn = [
            [
                "dataIndex" => 'name',
                'renderer' => 'read-only-text',
                'editable' => true,
                'align' => 'center',
                'title' => 'Name',
            ],
            [
                "dataIndex" => 'name_rendered',
                'renderer' => 'text',
                'align' => 'right',
            ],
        ];

        $allStatuses = LibStatuses::getFor($this->type);
        // dump($allStatuses);
        $columns = array_map(fn ($i) => [
            "dataIndex" => $i['name'],
            'renderer' => 'checkbox',
            'editable' => true,
            'align' => 'center',
            'title' => $i['title'],
            'width' => 100,
        ], $allStatuses);
        $columns = array_merge($firstColumn, $columns);
        // dump($columns);
        return $columns;
    }

    protected function getDataSource()
    {
        $allRoleSets = LibRoleSets::getAll();
        $dataInJson = Capabilities::getAllOf($this->type);
        // dump($dataInJson);
        $result = [];
        foreach ($allRoleSets as $status) {
            $name = $status['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = ['name' => $name];
            }
            $newItem['name_rendered'] = Str::appTitle($newItem['name']);
            $result[] = $newItem;
        }
        return $result;
    }
}
