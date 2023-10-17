<?php

namespace App\Http\Controllers\Utils;

trait TraitManageDefaultValueColumns
{
    protected function getColumns()
    {
        return [
            [
                "dataIndex" => "name",
                "renderer" => "read-only-text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "column_name",
                "renderer" => "read-only-text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "label",
                "renderer" => "text",
                // "editable" => true,
            ],
            [
                "dataIndex" => "validation",
                "editable" => true,
                "renderer" => "text4",
                'title' => "Validation Rules<br/><a class='text-blue-700 underline' target='_blank' href='https://laravel.com/docs/9.x/validation#available-validation-rules'>Open Laravel Doc</a>",
                'subTitle' => 'required|date_format:Y-m-d<br/>required|date_format:H:i:s<br/>nullable|date_format:Y-m-d H:i:s',
            ],
            [
                "dataIndex" => "validation_regex",
                "editable" => true,
                "renderer" => "text4",
                'title' => "Validation Regex<br/><a class='text-blue-700 underline' target='_blank' href='https://regex101.com/'>Open Regex101</a>",
                "properties" => ['placeholder' => '/^[0-9]+$/'],
            ],
            [
                "dataIndex" => "formula",
                "editable" => true,
                "renderer" => "dropdown",
                "cbbDataSource" => [
                    '',
                    'All_ClosedAt',
                    'All_ConcatNameWith123',
                    'All_DocId',
                    'All_DefinitionOfNew',
                    'All_OwnerId',
                    'All_SlugifyByName',
                    'User_PositionRendered',
                    // 'Wir_NameRendered',
                    'NCR_Report_Type',
                    'TSO_GetAssignee1',
                    '(not-yet)format_production',
                    '(not-yet)format_compliance',
                ],
                "properties" => ['strFn' => 'same'],
            ],
            [
                "dataIndex" => "default_value",
                "renderer" => "text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "label_extra",
                "renderer" => "text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "placeholder",
                "renderer" => "text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "control_extra",
                "renderer" => "text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "textarea_rows",
                "renderer" => "number4",
                "editable" => true,
                "properties" => ["placeholder" => 5],
            ],
        ];
    }
}
