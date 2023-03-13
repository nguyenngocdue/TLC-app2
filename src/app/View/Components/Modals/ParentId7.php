<?php

namespace App\View\Components\Modals;

use App\Utils\ClassList;
use Illuminate\View\Component;

class ParentId7 extends Component
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
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
    ) {
        if (old($name)) $this->selected = 1 * old($name);
        // dump($this->selected);
    }

    private function getDataSource($attr_name)
    {
        return [
            ['id' => 2001, 'name' => 'B001', $attr_name => 1001],
            ['id' => 2002, 'name' => 'B002', $attr_name => 1002],
            ['id' => 2003, 'name' => 'B003', $attr_name => 1003],
        ];
        // return $this->getAllIdMorphMany($attr_name);
    }


    private function renderJS($tableName, $objectTypeStr, $objectIdStr)
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
                'triggers' => [$objectTypeStr],
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
            'selected' => json_encode([is_numeric($this->selected) ? $this->selected * 1 : $this->selected]),
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
            // 'entity' => $this->type,
            'multiple' => $this->multiple ? true : false,
        ];
        $this->renderJS($tableName, 'modal_ot_team', $this->name);
        // dump($params);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
