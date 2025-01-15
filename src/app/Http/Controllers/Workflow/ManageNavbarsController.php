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
                'dataIndex' => "group",
                "renderer"  => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ["", "internal", "external", "third_party", "dev_tools", "data_admin"],
                'width' => 200,
            ],
            [
                'dataIndex' => 'title',
                'renderer' => 'text4',
                'editable' => true,
                'width' => 400,
            ],
            [
                'dataIndex' => 'href',
                'renderer' => 'text4',
                'editable' => true,
                'width' => 400,
            ],
            [
                'dataIndex' => 'icon_fa',
                'renderer' => 'text4',
                'editable' => true,
                'width' => 400,
            ],

        ];
    }
}
