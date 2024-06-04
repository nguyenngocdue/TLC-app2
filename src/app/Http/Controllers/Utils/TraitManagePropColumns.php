<?php

namespace App\Http\Controllers\Utils;

use App\Utils\Support\JsonControls;

trait TraitManagePropColumns
{
    protected function getColumns()
    {
        $controls = JsonControls::getControls();
        $isManagePropScreen = (isset($this->routeKey) && $this->routeKey == '_prp');
        return [
            [
                "dataIndex" => "action",
                "align" => "center",
                "width" => 100,
                // "fixed" => "left",
            ],
            [
                "dataIndex" => "move_to",
                "align" => "center",
                "renderer" => "text4",
                "editable" => true,
                "width" => 60,
                // "fixed" => "left",
            ],
            [
                "dataIndex" => "name",
                "renderer" => "read-only-text4",
                "editable" => true,
                // "fixed" => "left",
            ],
            [
                "dataIndex" => "column_name",
                "renderer" => $isManagePropScreen ? "read-only-text4" : "text4",
                "editable" => true,
                "invisible" => true,
            ],
            [
                "dataIndex" => "column_type",
                "renderer" => $isManagePropScreen ? "read-only-text4" : "text4",
                "editable" => true,
                'width' => 130,
            ],
            [
                "dataIndex" => "label",
                "renderer" => "text4",
                "editable" => true,
                'width' => 250,
            ],
            [
                "dataIndex" => "control",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => $controls,
                'width' => 250,
            ],
            [
                "dataIndex" => "align",
                'title' => "Align (Static Only)",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => [''],
            ],
            [
                "dataIndex" => "col_span",
                "editable" => true,
                "renderer" => "number4",
                'width' => 70,
                'properties' => ['placeholder' => 12],
            ],
            [
                "dataIndex" => "width",
                "editable" => true,
                "renderer" => "number4",
                'width' => 70,
                'title' => "Width (px)",
                'properties' => ['placeholder' => 100],
            ],
            [
                "dataIndex" => "hidden_view_all",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
            [
                "dataIndex" => "hidden_edit",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
            [
                "dataIndex" => "hidden_label",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
            [
                "dataIndex" => "hidden_filter",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
            [
                "dataIndex" => "hidden_print",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
            // [
            //     "dataIndex" => "hidden_template_print",
            //     "align" => "center",
            //     "editable" => true,
            //     "renderer" => "checkbox",
            //     'width' => 40,
            // ],
            [
                "dataIndex" => "read_only",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
            [
                "dataIndex" => "save_on_change",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
            [
                "title" => "Dupli-catable",
                "dataIndex" => "duplicatable",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
            [
                "dataIndex" => "new_line",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
            [
                "dataIndex" => "frozen_left",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
            [
                "dataIndex" => "frozen_right",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
            [
                "dataIndex" => "nowrap_view_all",
                "align" => "center",
                "editable" => true,
                "renderer" => "checkbox",
                'width' => 40,
            ],
        ];
    }
}
