<?php

namespace App\View\Components\Modals;

use App\Models\User_team_ot;
use App\Utils\ClassList;
use Illuminate\View\Component;

class ParentType7UserOt extends Component
{
    // use TraitMorphTo;
    /**
     * Create a new component instance.
     *
     * @return void
     */
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
        $list = User_team_ot::get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }

    private function renderJS($tableName, $dataSource)
    {
        $k = [$tableName => $dataSource,];
        $str = "";
        $str .= "<script>";
        $str .= " k = {...k, ..." . json_encode($k) . "};";
        $str .= "</script>";
        $str .= "\n";
        echo $str;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataSource = $this->getDataSource();
        $selectedFirst = $dataSource[0]['id'];
        // dump($dataSource);
        $tableName = "modal_" . $this->name;
        $params = [
            'name' => $this->name,
            'id' => $this->name,
            'selected' => json_encode([$this->selected]),
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
            // 'entity' => $this->type,
            'allowClear' => $this->allowClear,
        ];
        $params['selected'] = "[$selectedFirst]";
        $this->renderJS($tableName, $dataSource);
        // dump($params);
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
