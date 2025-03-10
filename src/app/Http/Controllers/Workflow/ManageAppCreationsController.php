<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\JsonControls;

class ManageAppCreationsController extends AbstractManageLibController
{
    protected $title = "Manage App Creations";
    protected $libraryClass = LibAppCreations::class;
    protected $route = "manageAppCreations";
    protected $allowedCreateNew = false;

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
                'title' => 'Required Params<br/>Comma Separated<br/>(att1, att2, att3, ...)',
                'dataIndex' => 'required_params',
                'renderer' => 'textarea4',
                'editable' => true,
            ],
            [
                'title' => 'PLEASE INITIATE THE CREATION VIA [...]',
                'dataIndex' => 'creation_links',
                'renderer' => 'textarea4',
                'editable' => true,
            ],
        ];
    }
}
