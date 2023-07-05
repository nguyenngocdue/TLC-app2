<?php

namespace App\Http\Controllers\Workflow;

class ManageProfileFieldsController extends AbstractManageLibController
{
    protected $title = "Manage Profile Fields";
    protected $libraryClass = LibProfileFields::class;
    protected $route = "manageProfileFields";
    protected $allowedCreateNew = false;

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
            // [
            //     'dataIndex' => "column_name",
            //     "renderer"  => 'read-only-text4',
            //     'editable' => true,
            // ],
            [
                'dataIndex' => 'profile_hidden',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'profile_readonly',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'me_hidden',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'me_readonly',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
        ];
    }
}
