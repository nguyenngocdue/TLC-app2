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
        if (isset($this->typeToLoadListener) && !is_null($this->typeToLoadListener)) {
            $this->renderJSForListener($this->typeToLoadListener);
        }
    }

    private function renderJSForListener($typeToLoadListener)
    {
        $a = $this->getListeners2($typeToLoadListener);
        $a = array_values(array_filter($a, fn ($x) => $x['column_name'] == $this->name));
        $listenersOfDropdown2 = [$a[0]];
        // dump($listenersOfDropdown2);

        $str = "\n";
        $str .= "<script>";
        $str .= " listenersOfDropdown2 = [...listenersOfDropdown2, ..." . json_encode($listenersOfDropdown2) . "];";
        $str .= "</script>";
        $str .= "\n";
        echo $str;
    }

    private function getParamsForHasDataSource()
    {
        return  [
            'name' => $this->name,
            'id' => $this->name,
            'selected' => $this->selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $this->tableName,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
            'multiple' => $this->multiple ? true : false,
            'allowClear' => $this->allowClear,
        ];
    }
}
