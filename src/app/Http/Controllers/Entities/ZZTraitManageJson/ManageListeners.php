<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibListenerActions;
use App\Utils\Support\Json\Listeners;
use App\Utils\Support\Json\Props;

class ManageListeners extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-listener";
    protected $routeKey = "_ltn";
    protected $jsonGetSet = Listeners::class;

    protected function getColumns()
    {
        $columns = array_values(array_map(fn($i) => $i['column_name'], Props::getAllOf($this->type)));
        $columns = array_merge([""], $columns);
        $listen_actions = [
            '',
            'reduce',
            'reduce_union',
            'assign',
            'dot',
            'expression',
            'date_offset',
            'number_to_words',
            'ajax_request_scalar',
            'emit_chain',
            'count_selected_values',

            'aggregate_from_table', // Field ONLY
            'trigger_change_all_lines', // Table ONLY
            'trigger_change_all_lines_except_current', // Table ONLY
            'set_table_column', // Table ONLY
        ];
        return [
            // [
            //     "dataIndex" => "action",
            //     "align" => "center",
            // ],
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
                "dataIndex" => "listen_action",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => $listen_actions,
                "sortBy" => 'value',
                "properties" => ["strFn" => 'same'],
                'width' => 200,
            ],
            [
                "dataIndex" => "triggers",
                "renderer" => "textarea4",
                "editable" => true,
                'width' => 150,
            ],
            [
                "dataIndex" => "listen_to_fields",
                "renderer" => "textarea4",
                "editable" => true,
            ],
            [
                "dataIndex" => "listen_to_attrs",
                "renderer" => "textarea4",
                "editable" => true,
            ],
            [
                "dataIndex" => "columns_to_set",
                "renderer" => "textarea4",
                "editable" => true,
            ],
            [
                "dataIndex" => "attrs_to_compare",
                "renderer" => "textarea4",
                "editable" => true,
                "properties" => ["placeholder" => 'id'],
            ],
            [
                "dataIndex" => "expression",
                "title" => "Expression or API",
                "renderer" => "textarea4",
                "editable" => true,
                'width' => 200,
            ],
            [
                "dataIndex" => "ajax_response_attribute",
                "renderer" => "text4",
                "editable" => true,
            ],
            [
                "dataIndex" => "ajax_form_attributes",
                "renderer" => "textarea4",
                "editable" => true,
                'width' => 200,
            ],
            [
                "dataIndex" => "ajax_item_attributes",
                "renderer" => "textarea4",
                "editable" => true,
                'width' => 200,
            ],
            [
                "dataIndex" => "ajax_default_values",
                "renderer" => "textarea4",
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
            $allActions = array_keys($newItem);
            $matrix = LibListenerActions::getAll();
            // dump($matrix);
            if (isset($newItem['listen_action'])) {
                $config = $matrix[$newItem['listen_action']];
                // unset($config['name']);
                foreach ($allActions as $action) {
                    if (in_array($action, ['name', 'column_name', 'listen_action'])) continue;
                    if (!(isset($config[$action]) && $config[$action] === 'true')) $newItem[$action] = "DO_NOT_RENDER";
                }
            } else {
                // $allActions = array_keys($matrix);
                // foreach ($allActions as $action) {
                //     $newItem[$action] = "DO_NOT_RENDER";
                // }
                $newItem['triggers'] = 'DO_NOT_RENDER';
                $newItem['listen_to_fields'] = 'DO_NOT_RENDER';
                $newItem['listen_to_attrs'] = 'DO_NOT_RENDER';
                $newItem['attrs_to_compare'] = 'DO_NOT_RENDER';
                $newItem['expression'] = 'DO_NOT_RENDER';
                $newItem['ajax_response_attribute'] = 'DO_NOT_RENDER';
                $newItem['ajax_form_attributes'] = 'DO_NOT_RENDER';
                $newItem['ajax_item_attributes'] = 'DO_NOT_RENDER';
                $newItem['ajax_default_values'] = 'DO_NOT_RENDER';
                $newItem['columns_to_set'] = 'DO_NOT_RENDER';
            }

            $isStatic = (isset($prop['column_type']) && in_array($prop['column_type'], ['static_heading', 'static_control']));
            if (!$isStatic) $result[] = $newItem;
        }


        foreach (array_keys($result) as $key) {
            $this->attachActionButtons($dataSource, $key, ['right_by_name']);
        }
        return $result;
    }
}
