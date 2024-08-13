<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use Illuminate\Support\Facades\Log;

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
        // dd($this->filterDetail);

        // Log::info($a);
        $listenersOfDropdown2 = [[
            'column_name' => 'sub_project_id',
            'listen_action' => 'reduce',
            'triggers' => [
                'project_id',
            ],
            'listen_to_fields' => [
                'sub_project_id',
            ],
            'listen_to_attrs' => [
                'project_id',
            ],
            'columns_to_set' => [],
            'attrs_to_compare' => [
                'id',
            ],
            'expression' => '',
            'ajax_response_attribute' => '',
            'ajax_form_attributes' => [],
            'ajax_item_attributes' => [],
            'ajax_default_values' => [],
            'table_name' => 'sub_projects',
            'listen_to_tables' => [
                'sub_projects',
            ],
        ]];
        return $listenersOfDropdown2;
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
            'id' => $this->id ?? $this->name,
            'name' => $this->name . $sign,
            'selected' => $this->selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $this->tableName,
            'readOnly' => $this->readOnly,
            'classList' => $classList,
            'multiple' => $this->multiple ? true : false,
            'allowClear' => $this->allowClear,
        ];
    }
}
