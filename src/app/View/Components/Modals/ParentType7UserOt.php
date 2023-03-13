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
    ) {
        if (old($name)) $this->selected = old($name);
    }

    private function getDataSource()
    {
        // return [
        //     ['id' => 1001, 'name' => 'A001'],
        //     ['id' => 1002, 'name' => 'A002'],
        //     ['id' => 1003, 'name' => 'A003'],
        // ];
        $list = User_team_ot::get()->toArray();
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
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
        ];
        $this->renderJS($tableName);
        // dump($params);
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
