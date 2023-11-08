<?php

namespace App\View\Components\Reports;
use App\Utils\ClassList;

trait TraitParentIdHasListenToParamsReport
{

    private function renderJS2($tableName, $objectTypeStr, $objectIdStr)
    {
        $attr_name = $tableName;
        $k = [$tableName => $this->getDataSource($attr_name),];
        // dump($this->referData, $this->referData1);
        $listenersOfDropdown2 = [
            [
                'listen_action' => 'reduce',
                'column_name' => $objectIdStr,
                'listen_to_attrs' => [$this->referData, $this->referData1],
                'listen_to_fields' => [$objectIdStr, $objectIdStr],
                'listen_to_tables' => [$tableName, $tableName],
                'table_name' => $tableName,
                // 'attrs_to_compare' => ['id'],
                'triggers' => [$this->referData, $this->referData1],
            ],
        ];
        $str = "\n";
        $str .= "<script>";
        $str .= " k = {...k, ..." . json_encode($k) . "};";
        $str .= " listenersOfDropdown2 = [...listenersOfDropdown2, ..." . json_encode($listenersOfDropdown2) . "];";
        $str .= "</script>";
        $str .= "\n";
        echo $str;
    }
    public function getListenToParamsReport()
    {
        
        $tableName = $this->name;
        $params = [
            'name' => $this->multiple ?  $this->name . '[]' : $this->name,
            'id' => $this->name,
            // 'selected' => $this->selected,
            'selected' => str_contains($this->selected, '["') || str_contains($this->selected, '[') ? $this->selected : json_encode([$this->selected]),
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
            'multiple' => $this->multiple ? true : false,
            'allowClear' => $this->allowClear ? $this->allowClear :0,
        ];
        // dump($params);
        $this->renderJS2($tableName, $this->referData, $this->name);
        return $params;
    }
}
