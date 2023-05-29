<?php

namespace App\View\Components\Reports;


use App\Utils\ClassList;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

abstract class ParentIdParamReports2 extends Component
{

    abstract protected function getDataSource($attr_name);
    protected $referData = '';
    protected $referData1 = '';

    public function __construct(
        private $name,
        private $selected = "",
        private $multiple = false,
        private $readOnly = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $allowClear = false,
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }
    private function renderJS($tableName, $objectTypeStr, $objectIdStr)
    {
        $attr_name = $tableName;
        $k = [$tableName => $this->getDataSource($attr_name),];
        // dump($this->referData, $this->referData1, $objectIdStr, $attr_name, $tableName, $objectTypeStr);
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
        $str = "";
        $str .= "<script>";
        $str .= " k = {...k, ..." . json_encode($k) . "};";
        $str .= " listenersOfDropdown2 = [...listenersOfDropdown2, ..." . json_encode($listenersOfDropdown2) . "];";
        $str .= "</script>";
        $str .= "\n";
        echo $str;
    }

    public function render()
    {
        $tableName = $this->name;
        $params = [
            'name' => $this->multiple ?  $this->name . '[]' : $this->name,
            'id' => $this->name,
            'selected' => $this->selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
            'multiple' => $this->multiple ? true : false,
            'allowClear' => $this->allowClear,
        ];


        $this->renderJS($tableName, $this->referData, $this->name);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
