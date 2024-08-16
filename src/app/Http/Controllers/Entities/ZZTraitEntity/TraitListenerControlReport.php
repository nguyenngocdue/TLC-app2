<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use Illuminate\Support\Str;

trait TraitListenerControlReport
{
    use TraitEntityListenDataSource;
    use TraitGetSuffixListenerControl;
    private function renderJSForK()
    {
        $tableName = $this->tableName;
        $k = [$tableName => $this->getDataSource(),];
        $str = "\n";
        $str .= "<script>";
        $str .= " k = {...k, ..." . json_encode($k) . "};";
        $str .= "</script>";
        $str .= "\n";
        echo $str;
        $this->renderJSForListener();
    }

    //Public for Sidebar Filter Subproject
    //Suffix is for case one form have multiple dropdown but same control (like sub projects in sidebar filter and sub projects in edit modal)
    public function getListenersOfDropdown2()
    {
        $filter = $this->filter;
        $listenReducerId = $filter->listen_reducer_id;

        if($listenReducerId) {
            $listenReducer = $this ->filter->getListenReducer;
            // dump($filter, $listenReducer);
            $listenersOfDropdown2 = [[
                "ajax_default_values" => [],
                "ajax_form_attributes" => [],
                "ajax_item_attributes" => [],
                "ajax_response_attribute" => "",
                "attrs_to_compare" => explode(',', $listenReducer->attrs_to_compare),
                "column_name" => $listenReducer->column_name,
                "columns_to_set" => $listenReducer->columns_to_set ? explode(',', $listenReducer->columns_to_set) :[],
                "expression" => "",
                "listen_action" => "reduce",
                "listen_to_fields" => explode(',', $listenReducer->listen_to_fields),
                "listen_to_attrs" => explode(',', $listenReducer->listen_to_attrs),
                "listen_to_tables" => explode(',', $listenReducer->listen_to_tables),
                "table_name" => Str::plural($filter->entity_type),
                "triggers" => explode(',', $listenReducer->triggers)
                ]];
                // dump($listenersOfDropdown2);
            return $listenersOfDropdown2;
        }
    }

    private function renderJSForListener()
    {
        $listenersOfDropdown2 = $this->getListenersOfDropdown2();

        if (is_array($listenersOfDropdown2)) {
            $str = "\n";
            $str .= "<script>";
            $str .= " listenersOfDropdown2 = [...listenersOfDropdown2, ..." . json_encode($listenersOfDropdown2) . "];";
            $str .= "</script>";
            $str .= "\n";
            echo $str;
        }
    }

    private function formatArrayString($input) {
        $input = trim($input, '[]');
        $elements = explode(',', $input);
        $formattedElements = array_map(function($element) {
            $element = trim($element);
            if (is_numeric($element)) {
                return $element;
            } else {
                return "\"$element\"";
            }
        }, $elements);
        return '[' . implode(',', $formattedElements) . ']';
    }

    private function getParamsForHasDataSource()
    {
        switch ($this->control) {
            case "radio-or-checkbox2":
                $classList = ClassList::RADIO_CHECKBOX;
                break;
            case "dropdown2":
                $classList = ClassList::DROPDOWN;
                break;
            default:
                $classList = "border border-gray-200 rounded (Unknown-classList-of-$this->control)";
                break;
        }
        $sign = $this->multiple ? '[]' : '';
        return  [
            'id' => $this->name ?? $this->id,
            'name' => $this->name . $sign,
            'selected' => $this->formatArrayString($this->selected),
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $this->tableName,
            'readOnly' => $this->readOnly,
            'classList' => $classList,
            'multiple' => $this->multiple ? true : false,
            'allowClear' => $this->allowClear,
        ];
    }
}
