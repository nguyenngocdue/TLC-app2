<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityListenDataSource;
use App\Models\Pj_sub_task;
use App\Models\Sub_project;
use App\Utils\ClassList;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class ModalFilterSubTask extends Component
{
    use TraitEntityListenDataSource;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $tableName,
        private $selected = "",
        private $multiple = false,
        private $readOnly = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $allowClear = false,
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        $dataSource = Pj_sub_task::select('id', 'name', 'description')->get();
        return $dataSource;
    }

    private function renderJS($tableName)
    {
        $k = [$tableName => $this->getDataSource(),];

        $a = $this->getListeners2('hr_timesheet_line');
        $a = array_values(array_filter($a, fn ($x) => $x['column_name'] == $this->name));
        $listenersOfDropdown2 = [$a[0]];
        // dump($listenersOfDropdown2);

        $str = "\n";
        $str .= "<script>";
        $str .= " k = {...k, ..." . json_encode($k) . "};";
        $str .= " listenersOfDropdown2 = [...listenersOfDropdown2, ..." . json_encode($listenersOfDropdown2) . "];";
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
        // dump("Selected: '" . $this->selected . "'");
        $tableName =  $this->tableName;
        $params = [
            'name' => $this->name,
            'id' => $this->name,
            'selected' => $this->selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
            // 'entity' => $this->type,
            'multiple' => $this->multiple ? true : false,
            'allowClear' => $this->allowClear,
        ];
        $this->renderJS($tableName);
        // dump($params);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
