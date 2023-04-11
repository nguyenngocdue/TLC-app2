<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Sub_project;
use App\Utils\ClassList;
use Illuminate\View\Component;

class ParamRunHistoryOption extends Component
{
    public function __construct(
        private $name,
        private $selected = "",
        private $multiple = false,
        // private $type,
        private $readOnly = false,
        private $allowClear = false,

    ) {
        if (old($name)) $this->selected = old($name);
    }

    private function getDataSource()
    {
        $dataSource = [['id' => 0, 'name' => 'View only last run'], ['id' => 1, 'name' => 'View all runs']];
        return $dataSource;
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
        $tableName = "modal_" . $this->name;
        $params = [
            'name' => $this->name,
            'id' => $this->name,
            'selected' => json_encode([(int)$this->selected]),
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
