<?php

namespace App\Http\Controllers\Workflow;

class ManageWidgetsController extends AbstractManageLibController
{
    protected $title = "Manage Widgets";
    protected $libraryClass = LibWidgets::class;
    protected $route = "manageWidgets";

    protected function getColumns()
    {
        return   [
            [
                'dataIndex' => "name",
                "renderer"  => 'read-only-text',
                'editable' => true,
            ],
            [
                'dataIndex' => "table_a",
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                'dataIndex' => "table_b",
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                'dataIndex' => "key_a",
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                'dataIndex' => "key_b",
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                'dataIndex' => "global_filter",
                'renderer' => 'text',
                'editable' => true,
            ],
        ];
    }
}
