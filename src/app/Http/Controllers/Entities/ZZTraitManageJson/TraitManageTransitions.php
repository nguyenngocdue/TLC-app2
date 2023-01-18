<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Transitions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitManageTransitions
{
    private function getColumnsTransition()
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

    private function getDataSourceTransition()
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

    public function indexTransition(Request $request)
    {
        return $this->indexObj($request, "dashboards.pages.manage-transition", '_tst');
    }

    public function storeTransition(Request $request)
    {
        return $this->storeObj($request, Transitions::class, '_tst');
    }
}
