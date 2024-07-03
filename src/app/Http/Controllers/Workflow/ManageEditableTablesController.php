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
                'width' => 250,
            ],
            [
                'dataIndex' => "editable-table",
                'title' => "Make this Table editable",
                "renderer"  => 'dropdown',
                'editable' => true,
                'cbbDataSource' => $entities,
                'width' => 250,
            ],
            [
                'dataIndex' => "button_add_a_new_line",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
                'width' => 60,
            ],
            [
                'dataIndex' => "button_add_from_a_list",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
                'width' => 60,
            ],
            [
                "dataIndex" => "item_datasource",
                "renderer"  => 'dropdown',
                'editable' => true,
                'cbbDataSource' => $entities,
                'width' => 250,
            ],
            [
                "dataIndex" => "eloquent_function_name",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                "dataIndex" => "group_datasource",
                "renderer"  => 'dropdown',
                'editable' => true,
                'cbbDataSource' => $entities,
                'width' => 250,
            ],

            [
                "dataIndex" => "foreign_key",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => "button_clone_from_tmpl",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
                'width' => 60,
            ],
            // [
            //     'dataIndex' => "button_get_lines",
            //     "renderer"  => 'checkbox',
            //     'editable' => true,
            //     'align' => "center",
            //     'width' => 60,
            // ],
            [
                'dataIndex' => "button_recalculate",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
                'width' => 60,
            ],

        ];
    }
}
