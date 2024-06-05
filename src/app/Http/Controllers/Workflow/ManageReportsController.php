<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\JsonControls;

class ManageReportsController extends AbstractManageLibController
{
    protected $title = "Manage Reports";
    protected $libraryClass = LibReports::class;
    protected $route = "manageReports";
    protected $groupByLength = 6;

    protected function getColumns()
    {
        $packages = JsonControls::getPackages();
        $breadcrumbGroups = JsonControls::getBreadcrumbGroup();
        // $subPackages = JsonControls::getSubPackages();
        // $subPackages = Entities::getAllPluralNames();
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
                'dataIndex' => "package_tab",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ['report'],
                'width' => 200,
                // "sortBy" => "value",
                // "properties" => ["strFn" => "appTitle"],
            ],
            [
                'dataIndex' => "package",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => $packages,
                // "sortBy" => "value",
                "properties" => ["strFn" => "appTitle"],
                'width' => 300,
            ],
            [
                'dataIndex' => "breadcrumb-group",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => $breadcrumbGroups,
                'width' => 300,
            ],
            [
                'dataIndex' => 'top_title',
                'renderer' => 'text4',
                'editable' => true,
                'width' => 300,
            ],
            [
                'dataIndex' => 'title',
                'renderer' => 'text4',
                'editable' => true,
                'width' => 300,
            ],
            [
                'dataIndex' => 'hidden',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 200,
            ],
        ];
    }
}
