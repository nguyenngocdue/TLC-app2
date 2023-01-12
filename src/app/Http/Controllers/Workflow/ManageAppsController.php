<?php

namespace App\Http\Controllers\Workflow;

class ManageAppsController extends AbstractManageLibController
{
    protected $title = "Manage Apps";
    protected $libraryClass = LibApps::class;
    protected $route = "manageApps";

    protected function getColumns()
    {
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
                "cbbDataSource" => [
                    ['value' => '', 'title' => ''],
                    ['value' => 'hr_&_admin', 'title' => 'HR & Admin'],
                    ['value' => 'project_management', 'title' => 'Project Management'],
                    ['value' => 'compliance', 'title' => 'Compliance'],
                    ['value' => 'operation', 'title' => 'Operation'],
                    ['value' => 'system', 'title' => 'System'],
                    ['value' => 'unit_test', 'title' => 'Unit Test'],
                ],
                // "sortBy" => "value",
            ],
            [
                'dataIndex' => 'title',
                'renderer' => 'text',
                'editable' => true,
            ],
        ];
    }
}
