<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\Entities;

class ManageEditableTablesController extends AbstractManageLibController
{
    protected $title = "Manage Editable Tables";
    protected $libraryClass = LibEditableTables::class;
    protected $route = "manageEditableTables";
    protected $groupByLength = 2;

    protected function getColumns()
    {
        $entities = Entities::getAllPluralNames();
        $entities = ['', ...$entities];
        return [
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
                'dataIndex' => "entity-type",
                "title" => "In Form Edit of",
                "renderer"  => 'dropdown',
                'editable' => true,
                'cbbDataSource' => $entities,
            ],
            [
                'dataIndex' => "editable-table",
                'title' => "Make this Table editable",
                "renderer"  => 'dropdown',
                'editable' => true,
                'cbbDataSource' => $entities,
            ],
            [
                'dataIndex' => "button_add_a_new_line",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
            ],
            [
                'dataIndex' => "button_add_from_a_list",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
            ],
            [
                'dataIndex' => "button_clone_from_tmpl",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
            ],
            [
                'dataIndex' => "button_get_lines",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
            ],
            [
                'dataIndex' => "button_recalculate",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
            ],

        ];
    }
}
