<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;

trait TraitListenerControl
{
    use TraitEntityListenDataSource;

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

    private function getListenersOfDropdown2()
    {
        $a = $this->getListeners2($this->typeToLoadListener);
        $a = array_values(array_filter($a, fn ($x) => $x['column_name'] == $this->name));
        $listenersOfDropdown2 = [$a[0]];
        return $listenersOfDropdown2;
    }

    private function renderJSForListener()
    {
        if (isset($this->typeToLoadListener) && !is_null($this->typeToLoadListener)) {
            $listenersOfDropdown2 = $this->getListenersOfDropdown2();

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
        return  [
            'name' => $this->name,
            'id' => $this->name,
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
