<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Sub_project;
use App\Utils\ClassList;
use Illuminate\View\Component;

class ParamSubProjectId extends Component
{
    public function __construct(
        private $name,
        private $selected = "",
        private $multiple = false,
        // private $type,
        private $readOnly = false,
    ) {
        // if (old($name)) $this->selected = old($name);
    }

    private function getDataSource()
    {
        $list = Sub_project::get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];

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
            // 'entity' => $this->type,
        ];
        $this->renderJS($tableName);
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
