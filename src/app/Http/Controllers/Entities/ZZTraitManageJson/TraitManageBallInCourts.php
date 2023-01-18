<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\BallInCourts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitManageBallInCourts
{
    private function getColumnsBallInCourt()
    {
        $allStatuses = LibStatuses::getFor($this->type);
        $firstColumns = [
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
            [
                "dataIndex" => 'ball-in-court',
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ['', 'creator', 'assignee_1', 'assignee_2', 'assignee_3', 'assignee_4', 'assignee_5', 'assignee_6', 'assignee_7', 'assignee_8', 'assignee_9',],
                // 'align' => 'center',
                // 'title' => 'Key',
            ],
        ];
        $columns = array_map(fn ($i) => [
            'dataIndex' => $i['name'],
            'renderer' => 'checkbox',
            'editable' => true,
            'align' => 'center',
            'width' => 10,
        ], $allStatuses);
        return array_merge($firstColumns, $columns);
    }

    private function getDataSourceBallInCourt()
    {
        $allStatuses = LibStatuses::getFor($this->type);
        $dataInJson = BallInCourts::getAllOf($this->type);
        $result = [];
        foreach ($allStatuses as $status) {
            $name = $status['name'];
            if (isset($dataInJson[$name])) {
                $result[] = $dataInJson[$name];
            } else {
                $result[] = ['name' => $name];
            }
        }
        // dump($allStatuses);
        // dump($dataInJson);
        // dump($result);
        return $result;
    }

    public function indexBallInCourt(Request $request)
    {
        return $this->indexObj($request, "dashboards.pages.manage-setting", '_bic');
    }

    public function storeBallInCourt(Request $request)
    {
        return $this->storeObj($request, BallInCourts::class, '_bic');
    }
}
