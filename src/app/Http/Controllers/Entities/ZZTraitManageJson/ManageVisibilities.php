<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\Visibilities;
use Illuminate\Support\Facades\Log;

class ManageVisibilities extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-visibility";
    protected $routeKey = "_vsb";
    protected $jsonGetSet = Visibilities::class;

    protected function getColumns()
    {
        $allStatuses = LibStatuses::getFor($this->type);
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
                "dataIndex" => 'hello',
                'renderer' => 'text',
                'editable' => true,
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
        $dataInJson = Visibilities::getAllOf($this->type);
        return $dataInJson;
    }
}
