<?php

namespace App\View\Components\Reports;


use App\Utils\ClassList;
use Illuminate\Support\Arr;

trait TraitParentIdParamsReport
{
    private function renderJS1($tableName, $objectTypeStr, $objectIdStr)
    {
        $attr_name = $tableName . '_parent_fake_id';
        $k = [$tableName => $this->getDataSource($attr_name),];
        $listenersOfDropdown2 = [
            [
                'listen_action' => 'reduce',
                'column_name' => $objectIdStr,
                'listen_to_attrs' => [$attr_name],
                'listen_to_fields' => [$objectIdStr],
                'listen_to_tables' => [$tableName],
                'table_name' => $tableName,
                // 'attrs_to_compare' => ['id'],
                'triggers' => [$objectTypeStr],
            ],
        ];
        // dump($listenersOfDropdown2);
        $str = "\n";
        $str .= "<script>";
        $str .= " k = {...k, ..." . json_encode($k) . "};";
        $str .= " listenersOfDropdown2 = [...listenersOfDropdown2, ..." . json_encode($listenersOfDropdown2) . "];";
        $str .= "</script>";
        $str .= "\n";
        echo $str;
    }

    public function getParamsReport()
    {
        $tableName = $this->name;
        $selected = str_contains($this->selected, '[') ? $this->selected : json_encode([$this->selected]);
        $params = [
            'name' => $this->multiple ?  $this->name . '[]' : $this->name,
            'id' => $this->name,
            'selected' => $selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
            'multiple' => $this->multiple ? true : false,
            'allowClear' => $this->allowClear,
        ];
        $referData = $this->hasListenTo ? $this->referData : '';
        $this->renderJS1($tableName, $referData, $this->name);
        return $params;
    }
}
