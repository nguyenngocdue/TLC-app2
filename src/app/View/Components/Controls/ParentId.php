<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class ParentId extends Component
{
    use TraitMorphTo;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $selected = "",
        private $multiple = false,
        private $type,
        private $readOnly = false,
    ) {
        if (old($name)) $this->selected = 1 * old($name);
        // dump($this->selected);
    }

    private function getDataSource($attr_name)
    {
        return $this->getAllIdMorphMany($attr_name);
    }

    private function renderJS($tableName, $objectTypeStr, $objectIdStr)
    {
        $attr_name = $tableName . '_xyz_id';
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
        echo $str;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $tableName = "morph_to_" . $this->name;
        $params = [
            'name' => $this->name,
            'id' => $this->name,
            'selected' => json_encode([is_numeric($this->selected) ? $this->selected * 1 : $this->selected]),
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'readOnly' => $this->readOnly,
            'className' => "bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white",
            'entity' => $this->type,
        ];
        $parentTypeName = $this->getParentTypeFromParentId($this->name);
        $this->renderJS($tableName, $parentTypeName, $this->name);
        // dump($params);
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
