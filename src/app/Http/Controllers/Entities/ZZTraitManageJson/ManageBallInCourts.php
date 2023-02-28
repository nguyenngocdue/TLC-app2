<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\BallInCourts;
use App\Utils\Support\Json\Transitions;
use App\Utils\Support\JsonControls;
use Illuminate\Support\Facades\Log;

class ManageBallInCourts extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-ball-in-court";
    protected $routeKey = "_bic";
    protected $jsonGetSet = BallInCourts::class;
    protected $storingWhiteList = ['name', 'ball-in-court-assignee', 'ball-in-court-monitors'];

    protected function getColumns()
    {
        $allStatuses = LibStatuses::getFor($this->type);
        $allAssignees = JsonControls::getAssignees();
        $allMonitors = JsonControls::getMonitors();
        $firstColumns = [
            [
                "dataIndex" => 'name',
                'renderer' => 'status',
                'align' => 'center',
                'title' => 'Name',
                'width' => 10,
            ],
            [
                "dataIndex" => 'name',
                'renderer' => 'read-only-text',
                'editable' => true,
                'align' => 'center',
                'title' => 'Key',
                'width' => 10,
            ],
            [
                "dataIndex" => 'ball-in-court-assignee',
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ['', 'creator', ...$allAssignees],
                'width' => 10,
            ],

        ];
        $columns = array_map(fn ($i) => [
            'dataIndex' => $i['name'],
            'renderer' => 'text',
            'align' => 'center',
            'width' => 10,
            'title' => $i['title'],
        ], $allStatuses);
        $columns = array_merge($firstColumns, $columns);
        array_push($columns,  [
            "dataIndex" => 'ball-in-court-monitors',
            'renderer' => 'dropdown',
            'editable' => true,
            "cbbDataSource" => ['',  ...$allMonitors],
            'properties' => ['strFn' => 'same'],
            'width' => 10,
        ],);
        return $columns;
    }

    protected function getDataSource()
    {
        $allStatuses = LibStatuses::getFor($this->type);
        $dataInJson = BallInCourts::getAllOf($this->type);
        $result = [];
        $workflow0 = Transitions::getAllOf($this->type);
        // dump($workflow0);
        $bic = BallInCourts::getAllOf($this->type);
        // dump($bic);
        foreach ($allStatuses as $status1) {
            $name = $status1['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = ['name' => $name];
            }
            if (isset($workflow0[$name])) {
                $workflow = $workflow0[$name];
                unset($workflow['name']);
                $workflowArray = [];
                foreach ($workflow as $k => $v) {
                    if ($v) $workflowArray[] = $k;
                }
                // dump($workflow);
                // dump($workflowArray);
                foreach (array_keys($allStatuses) as $status2) {
                    if (is_array($workflow) && in_array($status2, $workflowArray)) {
                        if (isset($bic[$name]['ball-in-court-assignee']) && isset($bic[$status2]['ball-in-court-assignee'])) {
                            $a1 = $bic[$name]['ball-in-court-assignee'];
                            $a2 = $bic[$status2]['ball-in-court-assignee'];
                            $newItem[$status2] = "$a1 -> $a2";
                        }
                    }
                }
            }

            $result[] = $newItem;
        }
        return $result;
    }
}
