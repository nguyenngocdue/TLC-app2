<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\Json\Listeners;
use App\Utils\Support\Json\Props;

class ManageListeners extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-listener";
    protected $routeKey = "_ltn";
    protected $jsonGetSet = Listeners::class;

    protected function getColumns()
    {
        $columns = array_values(array_map(fn ($i) => $i['column_name'], Props::getAllOf($this->type)));
        $columns = array_merge([""], $columns);
        return [
            // [
            //     "dataIndex" => "action",
            //     "align" => "center",
            // ],
            [
                "dataIndex" => "name",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "dataIndex" => "column_name",
                "renderer" => "read-only-text",
                "editable" => true,
            ],
            [
                "dataIndex" => "listen_action",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => ['', 'reduce', 'assign', 'dot', 'aggregate(not-yet)', 'expression', 'date_offset', 'number_to_words(not-yet)', 'ajax_request_scalar'],
                "sortBy" => 'value',
                "properties" => ["strFn" => 'same'],
            ],
            [
                "dataIndex" => "triggers",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "listen_to_fields",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "listen_to_attrs",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "attr_to_compare",
                "renderer" => "text",
                "editable" => true,
                "properties" => ["placeholder" => 'id'],
            ],
            [
                "dataIndex" => "expression",
                "title" => "Expression or API",
                "renderer" => "textarea",
                "editable" => true,
                'width' => 200,
            ],
            [
                "dataIndex" => "ajax_response_attribute",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "ajax_item_attribute",
                "renderer" => "text",
                "editable" => true,
            ],
            [
                "dataIndex" => "ajax_default_value",
                "renderer" => "text",
                "editable" => true,
            ],
        ];
    }

    protected function getDataSource()
    {
        $allProps = Props::getAllOf($this->type);
        $dataInJson = Listeners::getAllOf($this->type);
        // dump($dataInJson);
        $result = [];
        foreach ($allProps as $prop) {
            $name = $prop['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = ['name' => $name];
            }
            $newItem['column_name'] = $prop['column_name'];
            if (isset($newItem['listen_action'])) {
                switch ($newItem['listen_action']) {
                    case "expression":
                        $newItem['listen_to_fields'] = 'DO_NOT_RENDER';
                        $newItem['listen_to_attrs'] = 'DO_NOT_RENDER';
                        $newItem['attr_to_compare'] = 'DO_NOT_RENDER';
                        $newItem['ajax_response_attribute'] = 'DO_NOT_RENDER';
                        $newItem['ajax_item_attribute'] = 'DO_NOT_RENDER';
                        $newItem['ajax_default_value'] = 'DO_NOT_RENDER';
                        break;
                    case "ajax_request_scalar":
                        $newItem['listen_to_fields'] = 'DO_NOT_RENDER';
                        $newItem['listen_to_attrs'] = 'DO_NOT_RENDER';
                        $newItem['attr_to_compare'] = 'DO_NOT_RENDER';
                        break;
                    case "assign":
                    case "dot":
                        $newItem['attr_to_compare'] = 'DO_NOT_RENDER';
                        // break; //<<no break here
                    default:
                        $newItem['expression'] = 'DO_NOT_RENDER';
                        $newItem['ajax_response_attribute'] = 'DO_NOT_RENDER';
                        $newItem['ajax_item_attribute'] = 'DO_NOT_RENDER';
                        $newItem['ajax_default_value'] = 'DO_NOT_RENDER';
                        break;
                }
            } else {
                $newItem['triggers'] = 'DO_NOT_RENDER';
                $newItem['listen_to_fields'] = 'DO_NOT_RENDER';
                $newItem['listen_to_attrs'] = 'DO_NOT_RENDER';
                $newItem['attr_to_compare'] = 'DO_NOT_RENDER';
                $newItem['expression'] = 'DO_NOT_RENDER';
                $newItem['ajax_response_attribute'] = 'DO_NOT_RENDER';
                $newItem['ajax_item_attribute'] = 'DO_NOT_RENDER';
                $newItem['ajax_default_value'] = 'DO_NOT_RENDER';
            }
            $isStatic = (isset($prop['column_type']) && $prop['column_type'] === 'static');
            if (!$isStatic) $result[] = $newItem;
        }


        foreach (array_keys($result) as $key) {
            $this->attachActionButtons($dataSource, $key, ['right_by_name']);
        }
        return $result;
    }
}
