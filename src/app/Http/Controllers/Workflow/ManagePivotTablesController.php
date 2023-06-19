<?php

namespace App\Http\Controllers\Workflow;

class ManagePivotTablesController extends AbstractManageLibController
{
    protected $title = "Manage Pivot Tables";
    protected $libraryClass = LibPivotTables::class;
    protected $route = "managePivotTables";

    protected function getColumns()
    {
        return [
            [
                "dataIndex" => "action",
                "align" => "center",
            ],
            [
                'dataIndex' => "name",
                "renderer"  => 'read-only-text4',
                'editable' => true,
            ],
            [
                'dataIndex' => 'filters',
                'renderer' => 'text4',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'column_fields',
                'renderer' => 'text4',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'row_fields',
                'renderer' => 'text4',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'data_fields',
                'renderer' => 'text4',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'data_aggregations',
                'renderer' => 'text4',
                'editable' => true,
                'align' => 'center',
            ],
        ];
    }
}
