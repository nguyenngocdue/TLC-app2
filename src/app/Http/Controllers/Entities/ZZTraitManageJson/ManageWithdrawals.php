<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\Withdrawals;
use Illuminate\Support\Facades\Log;

class ManageWithdrawals extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-withdrawal";
    protected $routeKey = "_wdw";
    protected $jsonGetSet = Withdrawals::class;

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
                'renderer' => 'read-only-text4',
                'editable' => true,
                'align' => 'center',
                'title' => 'Key',
            ],
            [
                "dataIndex" => 'withdraw_to',
                'renderer' => 'dropdown',
                'editable' => true,
                'cbbDataSource' => [null, ...array_keys(LibStatuses::getFor($this->type))],
            ],
        ];
        return $firstColumn;
    }

    protected function getDataSource()
    {
        $allStatuses = LibStatuses::getFor($this->type);
        $dataInJson = Withdrawals::getAllOf($this->type);
        $result = [];
        foreach ($allStatuses as $status) {
            $name = $status['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = ['name' => $name];
            }
            // $newItem[$name] = 'DO_NOT_RENDER';
            $result[] = $newItem;
        }
        return $result;
    }
}
