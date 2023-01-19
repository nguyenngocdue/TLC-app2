<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\ActionButtons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitManageActionButtons
{
    private function getColumnsActionButton()
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
                'renderer' => 'read-only-text',
                'editable' => true,
                'align' => 'center',
                'title' => 'Key',
            ],
            [
                "dataIndex" => 'label',
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                "dataIndex" => 'tooltip',
                'renderer' => 'text',
                'editable' => true,
            ],
        ];
        return $columns;
    }

    private function getDataSourceActionButton()
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

    public function indexActionButton(Request $request)
    {
        return $this->indexObj($request, "dashboards.pages.manage-action-button", '_atb');
    }

    public function storeActionButton(Request $request)
    {
        return $this->storeObj($request, ActionButtons::class, '_atb');
    }
}
