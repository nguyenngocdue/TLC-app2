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
                'width' => 50,
            ],
            [
                'dataIndex' => "name",
                "renderer"  => 'read-only-text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'filters',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 170,
            ],
            [
                'dataIndex' => 'row_fields',
                'title' => 'Row Fields <br/>(for GROUP BY)',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 300,
            ],
            [
                'dataIndex' => 'column_fields',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 170,
            ],
            [
                'dataIndex' => 'value_index_fields',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 170,
            ],
            [
                'dataIndex' => 'data_fields',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 170,
            ],
            [
                'dataIndex' => 'data_aggregations',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 170,
            ],
            [
                'dataIndex' => 'lookup_tables',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 170,
            ],
            [
                'dataIndex' => 'sort_by',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 170,
            ],
        ];
    }
}
