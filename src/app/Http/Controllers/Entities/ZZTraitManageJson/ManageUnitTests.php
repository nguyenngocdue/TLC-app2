<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\Json\UnitTests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManageUnitTests extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-unit-test";
    protected $routeKey = "_unt";
    protected $jsonGetSet = UnitTests::class;

    protected function getColumns()
    {
        $columns = [
            [
                "dataIndex" => "action",
                "align" => "center",
            ],
            [
                "dataIndex" => "move_to",
                "align" => "center",
                "renderer" => "text",
                "editable" => true,
                "width" => 10,
            ],
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
                "dataIndex" => 'text_value',
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                "dataIndex" => 'number_value',
                'renderer' => 'number',
                'editable' => true,
            ],
            [
                "dataIndex" => 'checkbox',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                "dataIndex" => 'dropdown',
                'renderer' => 'dropdown',
                'editable' => true,
                'cbbDataSource' => ['', 'value_1', 'value_2', 'value_3'],
            ],
        ];
        return $columns;
    }

    protected function getDataSource()
    {
        $dataInJson = UnitTests::getAllOf($this->type);
        foreach ($dataInJson as $key => $columns) {
            // if (isset($columns['row_color']) && $columns['row_color'] === "green") continue;
            $this->attachActionButtons($dataInJson, $key, ['up', 'down', 'right_by_name']);
        }
        return $dataInJson;
    }
}
