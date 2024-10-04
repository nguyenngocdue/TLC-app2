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
                'width' => 225,
            ],
            [
                'dataIndex' => "editable-table",
                'title' => "Make this Table editable",
                "renderer"  => 'dropdown',
                'editable' => true,
                'cbbDataSource' => $entities,
                'width' => 225,
            ],
            [
                'dataIndex' => "button_add_a_new_line",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
                'width' => 40,
            ],
            [
                'dataIndex' => "button_clone_from_tmpl",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
                'width' => 40,
            ],
            [
                'dataIndex' => "button_recalculate",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
                'width' => 40,
            ],
            [
                'dataIndex' => "button_add_from_a_list",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => "center",
                'width' => 40,
            ],
            [
                "dataIndex" => "modal_body_name",
                "renderer"  => 'dropdown',
                'editable' => true,
                'width' => 300,
                'cbbDataSource' => JsonControls::getModalBodyName(),
            ],
            [
                "dataIndex" => "foreign_keys",
                "renderer"  => 'textarea4',
                'editable' => true,
                'width' => 300,
            ],
            [
                "dataIndex" => "data_type_to_get_id",
                "renderer"  => 'textarea4',
                'editable' => true,
                'width' => 100,
            ],
            [
                "dataIndex" => "button_create_item",
                "renderer"  => 'dropdown',
                'editable' => true,
                'align' => "center",
                'cbbDataSource' => $entities,
                'width' => 225,
            ],
        ];
    }

    protected function getDataSource()
    {
        $dataSource = parent::getDataSource();
        foreach ($dataSource as &$data) {
            if (!isset($data['button_add_from_a_list'])) {
                $data['modal_body_name'] = 'DO_NOT_RENDER';
                $data['foreign_keys'] = 'DO_NOT_RENDER';
                $data['data_type_to_get_id'] = 'DO_NOT_RENDER';
                $data['button_create_item'] = 'DO_NOT_RENDER';
            }
        }
        return $dataSource;
    }
}
