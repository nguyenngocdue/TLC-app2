<?php

namespace App\Http\Controllers\Workflow;

class ManageStatusesController extends AbstractManageLibController
{
    protected $title = "Manage Statuses";
    protected $libraryClass = LibStatuses::class;
    protected $route = "manageStatuses";

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
                'dataIndex' => "title",
                'renderer' => 'text4',
                'editable' => true,
                'width' => 300,
            ],
            [
                'dataIndex' => "color",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ["", "slate", "zinc", "neutral", "stone", "amber", "yellow", "lime", "emerald", "teal", "cyan", "sky", "blue", "indigo", "violet", "purple", "fuchsia", "pink", "rose", "green", "orange", "red", "gray"],
                "sortBy" => "value",
                'width' => 150,
            ],
            [
                'dataIndex' => "color_index",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ['100', '200', '300', '400', '500', '600', '700', '800', '900'],
                "sortBy" => "value",
            ],
            [
                'dataIndex' => 'icon',
                'renderer' => 'text4',
                'editable' => true,
                'width' => 500,
            ],
            // [
            //     'dataIndex' => "rendered",
            //     'renderer' => 'formatter4',
            //     'align' => 'center',
            //     'formatterName' => 'statusColorRendered',
            // ],
            [
                'dataIndex' => "name",
                'renderer' => 'formatter4',
                'align' => 'center',
                'formatterName' => 'statusColorRendered',
            ],
        ];
    }
}
