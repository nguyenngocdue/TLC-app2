<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\DBTable;
use App\Utils\Support\Listeners;

class ManageListeners extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-listener";
    protected $routeKey = "_ltn";
    protected $jsonGetSet = Listeners::class;

    protected function getColumns()
    {
        $columns = DBTable::getColumnNames($this->type);
        $columns = array_merge([""], $columns);
        // dump($columns);
        return [
            [
                "dataIndex" => "action",
                "align" => "center",
            ],
            [
                "dataIndex" => "name",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "dataIndex" => "column_name",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => $columns,
                "properties" => ["strFn" => 'same'],
            ],
            [
                "dataIndex" => "listen_action",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => ['', 'reduce', 'assign', 'dot', 'aggregate', 'expression', 'date_offset', 'number_to_words'],
                "properties" => ["strFn" => 'same'],
            ],
            [
                "dataIndex" => "triggers",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "listen_to_fields",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "listen_to_attrs",
                "renderer" => "text",
                "editable" => true,
            ],

        ];
    }

    protected function getDataSource()
    {
        $dataSource = Listeners::getAllOf($this->type);
        foreach (array_keys($dataSource) as $key) {
            $this->attachActionButtons($dataSource, $key, ['right_by_name']);
        }
        return $dataSource;
    }
}
