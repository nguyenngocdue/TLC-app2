<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\ActionButtons;
use Illuminate\Support\Facades\Log;

class ManageActionButtons extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-action-button";
    protected $routeKey = "_atb";
    protected $jsonGetSet = ActionButtons::class;

    protected function getColumns()
    {
        $columns = [
            [
                "dataIndex" => 'name',
                'renderer' => 'status',
                'align' => 'center',
                'title' => 'Name',
            ],
            [
                "dataIndex" => 'name',
                'renderer' => 'read-only-text4',
                'editable' => true,
                'align' => 'center',
                'title' => 'Key',
            ],
            [
                "dataIndex" => 'status_title',
                'renderer' => 'text4',
                'editable' => true,
                'properties' => ['placeholder' => 'As status TITLE'],
            ],
            [
                "dataIndex" => 'label',
                'renderer' => 'text4',
                'editable' => true,
                'properties' => ['placeholder' => 'As status TITLE'],
            ],
            [
                "dataIndex" => 'tooltip',
                'renderer' => 'text4',
                'editable' => true,
            ],
            [
                "dataIndex" => 'change_status_multiple',
                'renderer' => 'checkbox',
                'align' => 'center',
                'editable' => true,
            ],
        ];
        return $columns;
    }

    protected function getDataSource()
    {
        $allStatuses = LibStatuses::getFor($this->type);
        $dataInJson = ActionButtons::getAllOf($this->type);
        $result = [];
        foreach ($allStatuses as $status) {
            $name = $status['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = ['name' => $name];
            }
            $result[] = $newItem;
        }
        return $result;
    }
}
