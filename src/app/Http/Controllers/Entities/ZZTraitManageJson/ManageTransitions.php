<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\Transitions;
use Illuminate\Support\Facades\Log;

class ManageTransitions extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-transition";
    protected $routeKey = "_tst";
    protected $jsonGetSet = Transitions::class;

    protected function getColumns()
    {
        $firstColumn = [
            [
                "dataIndex" => 'name',
                'renderer' => 'status',
                'align' => 'center',
                'title' => 'Name',
            ],
            [
                "dataIndex" => 'name',
                'renderer' => 'read-only-text',
                'editable' => true,
                'align' => 'center',
                'title' => 'Key',
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
        $allStatuses = LibStatuses::getFor($this->type);
        $dataInJson = Transitions::getAllOf($this->type);
        // dump($dataInJson);
        $result = [];
        foreach ($allStatuses as $status) {
            $name = $status['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = ['name' => $name];
            }
            $newItem[$name] = 'invisible';
            $result[] = $newItem;
        }
        return $result;
    }
}
