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
                'dataIndex' => "package_tab",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ['', 'application', 'manage_admin',],
                // "sortBy" => "value",
                // "properties" => ["strFn" => "appTitle"],
                'width' => 100,
            ],
            [
                'dataIndex' => "package",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => $packages,
                // "sortBy" => "value",
                "properties" => ["strFn" => "appTitle"],
                'width' => 100,
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
                'width' => 200,
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
                'dataIndex' => 'edit_renderer',
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => [
                    '',
                    // 'props-renderer',
                    'checklist-sheet-renderer',
                    'exam-renderer',
                ],
            ],
            [
                'dataIndex' => 'show_renderer',
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => [
                    '',
                    // 'props-renderer',
                    'project-renderer',
                    'checklist-renderer',
                    'checklist-sheet-renderer',
                    'qr-app-renderer',
                ],
            ],

            [
                'dataIndex' => 'do_not_send_notification_mails',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'apply_approval_tree',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            // [
            //     'dataIndex' => 'view_all_absolute_table_width',
            //     'renderer' => 'checkbox',
            //     'editable' => true,
            //     'align' => 'center',
            // ],
            [
                'dataIndex' => "hidden",
                "renderer"  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'title' => "Hidden For non-admin Users",
            ],
            [
                'dataIndex' => "hidden_navbar",
                "renderer"  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'title' => "Hidden In Navbar Menu",
            ],
            [
                'dataIndex' => "disallowed_direct_creation",
                "renderer"  => 'checkbox',
                'align' => 'center',
                'editable' => true,
            ],
            [
                'dataIndex' => "show_in_my_view",
                "renderer"  => 'checkbox',
                'align' => 'center',
                'editable' => true,
            ],
            [
                'dataIndex' => "show_in_monitored_by_me",
                "renderer"  => 'checkbox',
                'align' => 'center',
                'editable' => true,
            ],
            [
                'dataIndex' => 'tutorial_link',
                'renderer' => 'text4',
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
