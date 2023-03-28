<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\Props;

class ManageDefaultValues extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-default-value";
    protected $routeKey = "_dfv";
    protected $jsonGetSet = DefaultValues::class;

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
                'title' => "Validation Rules<br/><a class='text-blue-700 underline' target='_blank' href='https://laravel.com/docs/9.x/validation#available-validation-rules'>Open Laravel Doc</a>"
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
                    'All_ConcatNameWith123',
                    'All_SlugifyByName',
                    'All_OwnerId',
                    'All_DocId',
                    'User_PositionRendered',
                    '(not-yet)Wir_NameRendered',
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
        ];
    }

    public function getDataSource()
    {
        $allProps = Props::getAllOf($this->type);
        $dataInJson = DefaultValues::getAllOf($this->type);
        $result = [];
        foreach ($allProps as $prop) {
            $name = $prop['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = [
                    'name' => $name,
                    'column_name' => $prop['column_name'],
                ];
            }
            $newItem['label'] = $prop['label'];
            if (isset($prop['column_type']) && $prop['column_type'] === 'static') $newItem['row_color'] = "amber";
            $result[] = $newItem;
        }
        return $result;
    }
}
