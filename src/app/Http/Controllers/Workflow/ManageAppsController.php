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
                "dataIndex" => "action",
                "align" => "center",
            ],
            [
                'dataIndex' => "name",
                "renderer"  => 'read-only-text',
                'editable' => true,
            ],
            [
                'dataIndex' => "hidden",
                "renderer"  => 'dropdown',
                'editable' => true,
                'title' => "Hidden For non-admin Users",
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
            [
                'dataIndex' => 'nickname',
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                'dataIndex' => 'status',
                'renderer' => 'dropdown',
                'editable' => true,
                'cbbDataSource' => ['', 'dev','beta'],
            ],
            [
                'dataIndex' => 'icon',
                'renderer' => 'text',
                'editable' => true,
            ],

        ];
    }
}
