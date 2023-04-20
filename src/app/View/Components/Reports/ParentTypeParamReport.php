<?php

namespace App\View\Components\Reports;

use App\Utils\ClassList;
use Illuminate\View\Component;

abstract class ParentTypeParamReport extends Component
{
    abstract protected function getDataSource();
    public function __construct(
        private $name,
        private $selected = "",
        private $multiple = false,
        private $readOnly = false,
        private $allowClear = false,
    ) {
        if (old($name)) $this->selected = old($name);
    }

    private function renderJS($tableName)
    {
        $k = [$tableName => $this->getDataSource(),];
        $str = "";
        $str .= "<script>";
        $str .= " k = {...k, ..." . json_encode($k) . "};";
        $str .= "</script>";
        $str .= "\n";
        echo $str;
    }

    public function render()
    {
        $tableName = $this->name;
        // dump(getType($this->selected));
        $params = [
            'name' => $this->name,
            'id' => $this->name,
            'selected' => json_encode([$this->selected]),
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
            'allowClear' => $this->allowClear,
        ];
        $this->renderJS($tableName);
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
