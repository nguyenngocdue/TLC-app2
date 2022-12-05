<?php

namespace App\View\Components\Controls;

use App\Utils\Support\Relationships;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Relationship extends Component
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

        if ($action !== 'edit') return "<x-feedback.alert message='Only show items on the update form' type='warning' />";

        $itemDB = $modelPath::find($id);
        $relationship = Relationships::getAllOf($this->type);

        foreach ($relationship as $value) {
            if ($colName !== $value['control_name']) continue;
            $dataSource = $itemDB->$colName->all();
            $typeDB =  $dataSource[0]->getTable() ?? "";
            if (count($dataSource) <= 0) return "<x-feedback.alert message='There is no item to be found' type='warning' />";
            $ins = "App\\Models\\" . Str::singular($typeDB);
            switch ($value['renderer_edit_param']) {
                case 'getManyIconParams':
                    $colSpan = (new $ins)->getManyIconParams()['colspan'];
                    return view('components.controls.manyIconParams')->with(compact('dataSource', 'colSpan'));
                case 'getManyLineParams':
                    $columns = (new $ins)->getManyLineParams();
                    return view('components.controls.manyLineParams')->with(compact('columns', 'dataSource'));
                default:
                    return "Unknown renderer_edit_param [{$value['renderer_edit_param']}]";
            }
        }
    }
}
