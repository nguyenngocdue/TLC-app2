<?php

namespace App\View\Components\Modals;

use App\Utils\ClassList;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ParentIdReportProdOrder extends Component
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
        $sql = "SELECT 
                    po.id AS id
                    ,po.name AS name
                    ,po.status AS po_status
                    ,po.sub_project_id AS $attr_name
                    ,po.prod_routing_id AS po_prod_routing_id
                    FROM prod_orders po
                    ORDER BY po.name
                ";
        $result = DB::select($sql);
        // dump($result);
        return $result;
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
                // 'attrs_to_compare' => ['id'],
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
        $this->renderJS($tableName, 'sub_project', $this->name);
        // dump($params);
        // dump($this->control);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
