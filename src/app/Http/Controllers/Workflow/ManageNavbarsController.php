<?php

namespace App\Http\Controllers\Workflow;

class ManageNavbarsController extends AbstractManageLibController
{
    protected $title = "Manage Navbars";
    protected $libraryClass = LibNavbars::class;
    protected $route = "manageNavbars";
    protected $groupBy = null;

    protected function getColumns()
    {
        return   [
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
                'dataIndex' => 'title',
                'renderer' => 'text4',
                'editable' => true,
            ],
            [
                'dataIndex' => 'href',
                'renderer' => 'text4',
                'editable' => true,
            ],
            [
                'dataIndex' => 'icon_fa',
                'renderer' => 'text4',
                'editable' => true,
            ],

        ];
    }
}
