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
                "renderer"  => 'read-only-text',
                'editable' => true,
            ],
            [
                'dataIndex' => "title",
                'renderer' => 'text',
                'editable' => true,
            ],
            [
                'dataIndex' => "color",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ["", "slate", "zinc", "neutral", "stone", "amber", "yellow", "lime", "emerald", "teal", "cyan", "sky", "blue", "indigo", "violet", "purple", "fuchsia", "pink", "rose", "green", "orange", "red", "gray"],
                "sortBy" => "value",
            ],
            [
                'dataIndex' => "color_index",
                'renderer' => 'dropdown',
                'editable' => true,
                "cbbDataSource" => ['100', '200', '300', '400', '500', '600', '700', '800', '900'],
                "sortBy" => "value",
            ],
            [
                'dataIndex' => "rendered",
                'renderer' => 'formatter',
                'formatterName' => 'statusColorRendered',
            ],
        ];
    }
}
