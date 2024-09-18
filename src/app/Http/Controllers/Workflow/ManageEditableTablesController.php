<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\Entities;
use App\Utils\Support\JsonControls;

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
                'dataIndex' => "button_clone_from_tmpl",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
                'width' => 60,
            ],
            [
                'dataIndex' => "button_recalculate",
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
            // [
            //     "dataIndex" => "item_datasource",
            //     "renderer"  => 'dropdown',
            //     'editable' => true,
            //     'cbbDataSource' => $entities,
            //     'width' => 250,
            // ],
            // [
            //     "dataIndex" => "eloquent_function_name",
            //     "renderer"  => 'text4',
            //     'editable' => true,
            //     'width' => 200,
            // ],
            // [
            //     "dataIndex" => "group_datasource",
            //     "renderer"  => 'dropdown',
            //     'editable' => true,
            //     'cbbDataSource' => $entities,
            //     'width' => 250,
            // ],

            [
                "dataIndex" => "modal_body_name",
                "renderer"  => 'dropdown',
                'editable' => true,
                'width' => 300,
                'cbbDataSource' => JsonControls::getModalBodyName(),
            ],
            [
                "dataIndex" => "foreign_key",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 300,
            ],

        ];
    }

    protected function getDataSource()
    {
        $dataSource = parent::getDataSource();
        foreach ($dataSource as &$data) {
            if (!isset($data['button_add_from_a_list'])) {
                // $data['item_datasource'] = 'DO_NOT_RENDER';
                // $data['eloquent_function_name'] = 'DO_NOT_RENDER';
                // $data['group_datasource'] = 'DO_NOT_RENDER';
                $data['modal_body_name'] = 'DO_NOT_RENDER';
                $data['foreign_key'] = 'DO_NOT_RENDER';
            }
        }
        return $dataSource;
    }
}
