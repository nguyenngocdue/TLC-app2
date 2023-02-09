<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\Json\Relationships;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class ParentId extends Component
{
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
    ) {
    }

    private function getParentTypeFromParentId($parent_id_name)
    {
        // $result = [];
        $relationships = Relationships::getAllOf($this->type);
        $dummyInstance = new (Str::modelPathFrom($this->type));
        $elq = $dummyInstance->eloquentParams;

        foreach ($relationships as $relationship) {
            if ($relationship['relationship'] === 'morphTo') {
                $control_name = $relationship['control_name'];
                $elqParams = $elq[$control_name];
                // $result[$control_name]['parent_type_name'] = $elqParams[2];
                // $result[$control_name]['parent_id_name'] = $elqParams[3];

                if ($parent_id_name == $elqParams[3]) return $elqParams[2];
            }
        }
        // dump($elq, $relationships);
        // return $result;
    }

    private function getDataSource($attr_name)
    {
        $result = [
            ['id' => 11, 'name' => 'Bai 1', $attr_name => 'a'],
            ['id' => 21, 'name' => 'Bai 2', $attr_name => 'b'],
            ['id' => 31, 'name' => 'Bai 3', $attr_name => 'c'],
        ];
        return $result;
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
            'selected' => json_encode([1 * $this->selected]),
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'className' => "bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white",
        ];
        $parentTypeName = $this->getParentTypeFromParentId($this->name);
        $this->renderJS($tableName, $parentTypeName, $this->name);
        // dump($params);
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
