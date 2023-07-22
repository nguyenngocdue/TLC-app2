<?php

namespace App\Http\Controllers\Utils;

use App\Utils\Support\JsonControls;

trait TraitManageRelationshipColumns
{
    protected function getColumns()
    {
        $viewAllControls = JsonControls::getRendererViewAll();
        $editControls = JsonControls::getRendererEdit();
        return [
            [
                "dataIndex" => "action",
                "align" => "center",
            ],
            [
                "dataIndex" => "name",
                "renderer" => "read-only-text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "relationship",
                "renderer" => "read-only-text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "control_name",
                "renderer" => "read-only-text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "renderer_view_all",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => $viewAllControls,
            ],
            [
                "dataIndex" => "renderer_view_all_param",
                "editable" => true,
                "renderer" => "text4",
            ],
            [
                "dataIndex" => "renderer_view_all_unit",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => JsonControls::getParamUnits(),
            ],
            [
                "dataIndex" => "renderer_edit",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => $editControls,
            ],
            [
                "dataIndex" => "renderer_edit_param",
                "editable" => true,
                "renderer" => "text4",
            ],
            [
                "dataIndex" => "control",
                "editable" => true,
                "renderer" => "read-only-text4",
            ],
            [
                "dataIndex" => "filter_columns",
                "editable" => true,
                "renderer" => "text4",
            ],
            [
                "dataIndex" => "filter_values",
                "editable" => true,
                "renderer" => "text4",
            ],
            [
                "dataIndex" => "radio_checkbox_colspan",
                "editable" => true,
                "renderer" => "number4",
                'properties' => ['placeholder' => 4],
            ],
            [
                "dataIndex" => "let_user_choose_when_one_item",
                "editable" => true,
                "renderer" => "checkbox",
                'align' => "center",
                // 'properties' => ['placeholder' => 4],
            ],
        ];
    }
}
