<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\Relationships;
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
    ) {
    }

    private function getDataSource($itemDB, $colName, $showAll = false)
    {
        $eloquentParam = $itemDB->eloquentParams[$colName];
        //TODO: This is to prevent from a crash
        if ($eloquentParam[0] === 'morphToMany') return [];

        if (isset($eloquentParam[2])) $relation = $itemDB->{$eloquentParam[0]}($eloquentParam[1], $eloquentParam[2]);
        elseif (isset($eloquentParam[1])) $relation = $itemDB->{$eloquentParam[0]}($eloquentParam[1]);
        elseif (isset($eloquentParam[0])) $relation = $itemDB->{$eloquentParam[0]}();
        $perPage = $showAll ? 10000 : 10;
        return $relation->getQuery()->paginate($perPage, ['*'], $colName);
    }

    private function getModelOfEloquentParam($itemDB, $colName,)
    {
        $eloquentParam = $itemDB->eloquentParams[$colName];
        return $eloquentParam[1];
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
        $action = CurrentRoute::getControllerAction();

        if ($action !== 'edit') return "";


        $relationship = Relationships::getAllOf($this->type);

        $theValue = array_filter($relationship, fn ($value) => $value['control_name'] === $colName);
        if (empty($theValue)) return "<x-feedback.alert message='Column [$colName] can not be found in control_name of Relationship screen.' type='warning' />";
        $value = $theValue["_" . $colName];

        $itemDB = $modelPath::find($id);
        if (is_null($itemDB->$colName)) return "<x-feedback.alert message='There is no item to be found.' type='warning' />";

        // $dataSource = $itemDB->$colName->all();
        $renderer_edit = $value['renderer_edit'];
        $showAll = $renderer_edit === "many_icons";
        $dataSource = $this->getDataSource($itemDB, $colName, $showAll);
        $smallModel = $this->getModelOfEloquentParam($itemDB, $colName);
        $instance = new $smallModel;

        $fn = $value['renderer_edit_param'];
        if (!method_exists($instance, $fn))  $fn = '';
        $typeDB =  isset($dataSource[0]) ? $dataSource[0]->getTable() : "";
        $columns = ($fn === '')
            ? [
                ["dataIndex" => 'id', "renderer" => "id", "type" => $typeDB, "align" => "center"],
                ["dataIndex" => 'name'],
            ]
            : $instance->$fn();

        switch ($renderer_edit) {
            case "many_icons":
                $colSpan =  Helper::getColSpan($colName, $type);
                foreach ($dataSource as &$item) {
                    $item['href'] = route($typeDB . '.edit', $item->id);
                    $item['gray'] = $item['resigned'];
                }
                $dataSource = $dataSource->all(); // Force LengthAwarePaginator to Array
                return view('components.controls.many-icon-params')->with(compact('dataSource', 'colSpan'));
            case "many_lines":
                return view('components.controls.many-line-params')->with(compact('columns', 'dataSource', 'fn'));
            default:
                return "Unknown renderer_edit [$renderer_edit] in Relationship Screen, pls select ManyIcons or ManyLines";
        }
    }
}
