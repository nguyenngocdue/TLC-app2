<?php

namespace App\View\Components\Controls;

use App\Utils\Support\Relationships;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class RelationshipRenderer extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $id, private $type, private $colName, private $tablePath, private $action)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $colName = $this->colName;
        $modelPath = $this->tablePath;
        $type = $this->type;
        $id = $this->id;
        $action = $this->action;

        $itemDB = $modelPath::find($id);
        $relationship = Relationships::getAllOf($this->type);
        if ($action === 'edit') {
            foreach ($relationship as $value) {
                if ($colName !== $value['control_name']) continue;
                $dataSource = $itemDB->$colName->all();
                if (count($dataSource) <= 0) return "<x-feedback.alert message='There is no item to be found' type='warning' />";
                $typeDB =  $dataSource[0]->getTable() ?? "";
                $ins = "App\\Models\\" . Str::singular($typeDB);
                $renderer_edit = $value['renderer_edit'];
                $fn = $value['renderer_edit_param'];
                if ($fn === '') {
                    $columns = [["dataIndex" => 'id', "renderer" => "id", "type" => $typeDB, "align" => "center"], ["dataIndex" => 'name']];
                } else {
                    $columns =  (new $ins)->$fn();
                }
                switch ($renderer_edit) {
                    case "many_icons":
                        $colSpan = $columns['colspan'] ?? 6;
                        return view('components.controls.manyIconParams')->with(compact('dataSource', 'colSpan', 'typeDB'));
                    case "many_lines":
                        return view('components.controls.manyLineParams')->with(compact('columns', 'dataSource'));
                    default:
                        return "Unknown renderer_edit [$renderer_edit]";
                }
            }
        }
    }
}
