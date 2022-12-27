<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Models\Prod_routing;
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
    public function __construct(
        private $id,
        private $type,
        private $colName,
        private $modelPath,
        private $action,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $colName = $this->colName;
        $modelPath = $this->modelPath;
        $type = $this->type;
        $id = $this->id;
        $action = $this->action;

        if ($action !== 'edit') return "";


        $relationship = Relationships::getAllOf($this->type);

        $theValue = array_filter($relationship, fn ($value) => $value['control_name'] === $colName);
        if (empty($theValue)) return "<x-feedback.alert message='Column [$colName] can not be found in control_name of Relationship screen.' type='warning' />";
        $value = $theValue["_" . $colName];

        $itemDB = $modelPath::find($id);
        if (is_null($itemDB->$colName)) return "<x-feedback.alert message='There is no item to be found.' type='warning' />";

        // $dataSource = $itemDB->$colName->all();

        $eloquentParam = $itemDB->eloquentParams[$colName];
        if (isset($eloquentParam[2])) $relation = $itemDB->{$eloquentParam[0]}($eloquentParam[1], $eloquentParam[2]);
        elseif (isset($eloquentParam[1])) $relation = $itemDB->{$eloquentParam[0]}($eloquentParam[1]);
        elseif (isset($eloquentParam[0])) $relation = $itemDB->{$eloquentParam[0]}();
        $dataSource = $relation->getQuery()->paginate(10)->all();


        if (count($dataSource) <= 0) return "<x-feedback.alert message='There is no item to be found.' type='warning' />";
        $typeDB =  $dataSource[0]->getTable() ?? "";
        $model = "App\\Models\\" . Str::singular($typeDB);
        $instance = new $model;

        $fn = $value['renderer_edit_param'];
        if (!method_exists($instance, $fn))  $fn = '';
        $columns = ($fn === '')
            ? [["dataIndex" => 'id', "renderer" => "id", "type" => $typeDB, "align" => "center"], ["dataIndex" => 'name']]
            : $instance->$fn();

        $renderer_edit = $value['renderer_edit'];
        switch ($renderer_edit) {
            case "many_icons":
                $colSpan =  Helper::setColSpan($colName, $type);
                foreach ($dataSource as &$item) $item['href'] = route($typeDB . '.edit', $item->id);
                return view('components.controls.manyIconParams')->with(compact('dataSource', 'colSpan'));
            case "many_lines":
                return view('components.controls.manyLineParams')->with(compact('columns', 'dataSource', 'fn'));
            default:
                return "Unknown renderer_edit [$renderer_edit] in Relationship Screen, pls select ManyIcons or ManyLines";
        }
    }
}
