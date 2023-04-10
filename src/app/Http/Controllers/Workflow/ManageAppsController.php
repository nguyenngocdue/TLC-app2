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
                "renderer"  => 'read-only-text4',
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
                'renderer' => 'text4',
                'editable' => true,
            ],
            [
                'dataIndex' => 'nickname',
                'renderer' => 'text4',
                'editable' => true,
            ],
            [
                'dataIndex' => 'doc_id_format_column',
                'renderer' => 'text4',
                'editable' => true,
            ],
            [
                'dataIndex' => 'status',
                'renderer' => 'dropdown',
                'editable' => true,
                'cbbDataSource' => ['', 'dev', 'beta'],
            ],
            [
                'dataIndex' => 'apply_approval_tree',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => "hidden",
                "renderer"  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'title' => "Hidden For non-admin Users",
            ],
            [
                'dataIndex' => "disallowed_direct_creation",
                "renderer"  => 'checkbox',
                'align' => 'center',
                'editable' => true,
            ],
            [
                'dataIndex' => 'icon',
                'renderer' => 'text4',
                'editable' => true,
            ],

        ];
    }
}
