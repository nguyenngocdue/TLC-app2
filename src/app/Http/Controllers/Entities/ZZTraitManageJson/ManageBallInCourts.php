<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\BallInCourts;
use App\Utils\Support\Json\Transitions;
use Illuminate\Support\Facades\Log;

class ManageBallInCourts extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-setting";
    protected $routeKey = "_bic";
    protected $jsonGetSet = BallInCourts::class;
    protected $storingWhiteList = ['name', 'ball-in-court'];

    protected function getColumns()
    {
        $allStatuses = LibStatuses::getFor($this->type);
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
                "dataIndex" => 'ball-in-court',
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ['', 'creator', 'assignee_1', 'assignee_2', 'assignee_3', 'assignee_4', 'assignee_5', 'assignee_6', 'assignee_7', 'assignee_8', 'assignee_9',],
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
        return array_merge($firstColumns, $columns);
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
                        if (isset($bic[$name]['ball-in-court']) && isset($bic[$status2]['ball-in-court'])) {
                            $a1 = $bic[$name]['ball-in-court'];
                            $a2 = $bic[$status2]['ball-in-court'];
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
