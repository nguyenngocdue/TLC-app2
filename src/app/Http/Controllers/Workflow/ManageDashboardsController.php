<?php

namespace App\Http\Controllers\Workflow;

class ManageDashboardsController extends AbstractManageLibController
{
    protected $title = "Manage Dashboards";
    protected $libraryClass = LibDashboards::class;
    protected $route = "manageDashboards";
    protected $groupBy = false;
    // protected $groupByLength = 2;

    protected function getColumns()
    {
        $columns =   [
            [
                "dataIndex" => "action",
                "align" => "center",
                'width' => 40,
            ],
            [
                'dataIndex' => "name",
                'title' => "Discipline ID",
                "renderer"  => 'read-only-text4',
                'editable' => true,
            ],
            [
                'dataIndex' => "title",
                "renderer"  => 'read-only-text4',
                'editable' => true,
                'width' => 200,
            ],

            //Dashboard
            [
                "dataIndex" => "show_column_progress",
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 50,
            ],
            [
                "dataIndex" => "show_all_ics_by_sub_project_client_box",
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 50,
            ],
            [
                "dataIndex" => "show_some_routings_per_user",
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 50,
            ],

            [
                "dataIndex" => "show_some_ics_by_sign_off_box",
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 50,
            ],
            [
                "dataIndex" => "show_some_ics_by_shipping_agent_box",
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 50,
            ],
            [
                "dataIndex" => "show_some_ics_by_council_member_box",
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 50,
            ],
            //Checklist
            [
                "dataIndex" => "be_able_to_change_checkpoint",
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 50,
            ],
            [
                "dataIndex" => "be_able_to_upload_photo_checkpoint",
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 50,
            ],
            [
                "dataIndex" => "be_able_to_comment_checkpoint",
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 50,
            ],
        ];

        return $columns;
    }
}
