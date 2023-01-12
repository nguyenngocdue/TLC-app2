<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\JsonControls;

class ManageAppsController extends AbstractManageLibController
{
    protected $title = "Manage Apps";
    protected $libraryClass = LibApps::class;
    protected $route = "manageApps";

    protected function getColumns()
    {
        $packages = JsonControls::getPackages();
        $subPackages = JsonControls::getSubPackages();
        return   [
            [
                'dataIndex' => "name",
                "renderer"  => 'read-only-text',
                'editable' => true,
            ],
            [
                'dataIndex' => "hidden",
                "renderer"  => 'dropdown',
                'editable' => true,
            ],
            [
                'dataIndex' => "package",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => $packages,
                // "sortBy" => "value",
                "properties" => ["strFn" => "appTitle"],
            ],
            [
                'dataIndex' => "sub_package",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => $subPackages,
                "sortBy" => "value",
                "properties" => ["strFn" => "appTitle"],
            ],
            [
                'dataIndex' => 'title',
                'renderer' => 'text',
                'editable' => true,
            ],
        ];
    }
}
