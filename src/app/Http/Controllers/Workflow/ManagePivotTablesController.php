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
                'fixed' => 'left',
            ],
            [
                'dataIndex' => "name",
                "renderer"  => 'read-only-text4',
                'editable' => true,
                'width' => 50,
                'fixed' => 'left',
            ],
            [
                'dataIndex' => 'is_raw',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 50,
                'fixed' => 'left',
            ],
            [
                'dataIndex' => 'is_render_row_fields',
                'title' => 'Render Row Fields <br/>(Using When Checking Is Raw)',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 300,
                'fixed' => 'left',
            ],
            [
                'dataIndex' => 'filters',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 300,
            ],
            [
                'dataIndex' => 'row_fields',
                'title' => 'Row Fields <br/>(for GROUP BY)',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 400,
            ],
            [
                'dataIndex' => 'insert_column_row_fields',
                'title' => 'Insert Column Fields <br/>(for Row Fields)',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 400,
            ],
            [
                'dataIndex' => 'column_fields',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 400,
            ],
            // [
            //     'dataIndex' => 'value_index_fields',
            //     'renderer' => 'textarea4',
            //     'editable' => true,
            //     'align' => 'center',
            //     'width' => 200,
            // ],
            // [
            //     'dataIndex' => 'data_aggregations',
            //     'renderer' => 'textarea4',
            //     'editable' => true,
            //     'align' => 'center',
            //     'width' => 100,
            // ],
            [
                'dataIndex' => 'data_fields',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 300,
            ],
            // [
            //     'dataIndex' => 'lookup_tables',
            //     'renderer' => 'textarea4',
            //     'editable' => true,
            //     'align' => 'center',
            //     'width' => 200,
            // ],
            [
                'dataIndex' => 'sort_by',
                'renderer' => 'textarea4',
                'editable' => true,
                'align' => 'center',
                'width' => 300,
            ],
        ];
    }
}
