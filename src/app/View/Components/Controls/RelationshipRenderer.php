<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;

class RelationshipRenderer extends Component
{
    private static $table00Count = 1;
    private $table01Name;
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
        $this->table01Name = "table" . str_pad(static::$table00Count++, 2, 0, STR_PAD_LEFT);
    }

    private function getDataSource($row, $colName, $showAll = false)
    {
        $eloquentParam = $row->eloquentParams[$colName];
        //TODO: This is to prevent from a crash
        if ($eloquentParam[0] === 'morphToMany') return [];

        if (isset($eloquentParam[2])) $relation = $row->{$eloquentParam[0]}($eloquentParam[1], $eloquentParam[2]);
        elseif (isset($eloquentParam[1])) $relation = $row->{$eloquentParam[0]}($eloquentParam[1]);
        elseif (isset($eloquentParam[0])) $relation = $row->{$eloquentParam[0]}();
        $perPage = $showAll ? 10000 : 10;
        return $relation->getQuery()->paginate($perPage, ['*'], $colName);
    }

    private function makeReadOnlyColumns($columns, $sp, $tableName)
    {
        // dump($sp);
        $result = [];
        foreach ($columns as $column) {
            $newColumn = $column;
            // if (!isset($sp['props']["_" . $column['dataIndex']])) die();
            $prop = $sp['props']["_" . $column['dataIndex']];
            $newColumn['title'] = $column['title'] ?? $prop['label'] . " <br/>" . $prop['control'];
            switch ($prop['control']) {
                case 'id':
                    $newColumn['renderer'] = 'id';
                    $newColumn['type'] = $tableName;
                    $newColumn['align'] = 'center';
                    break;
                case 'dropdown':
                    $dataIndex = $prop['relationships']['control_name_function'];
                    $newColumn['dataIndex'] = $dataIndex;
                    $newColumn['renderer'] = 'column';
                    $newColumn['rendererParam'] = $column['rendererParam'] ?? 'name';
                    break;
                case 'status':
                    $newColumn['renderer'] = 'status';
                    $newColumn['align'] = 'center';
                    break;
                case 'number':
                    $newColumn['align'] = 'right';
                    break;
                case 'toggle':
                    $newColumn['renderer'] = 'toggle';
                    $newColumn["align"] = "center";
                    break;
                default:
                    $newColumn['renderer'] = "text";
                    break;
            }
            $result[] = $newColumn;
        }
        // dump($result);
        return $result;
    }

    private function makeEditableColumns($columns, $sp, $tableName)
    {
        $result = [];
        foreach ($columns as $column) {
            $newColumn = $column;
            $prop = $sp['props']["_" . $column['dataIndex']];
            $newColumn['title'] = $column['title'] ?? $prop['label'] . " <br/>" . $prop['control'];
            switch ($prop['control']) {
                case 'id':
                    $newColumn['renderer'] = 'read-only-text';
                    $newColumn['editable'] = true;
                    $newColumn['align'] = 'center';
                    break;
                case 'status':
                    $newColumn['renderer'] = 'dropdown';
                    $newColumn['editable'] = true;
                    $newColumn['cbbDataSource'] = array_keys(LibStatuses::getFor($tableName));
                    break;
                case 'textarea':
                    $newColumn['renderer'] = 'textarea';
                    $newColumn['editable'] = true;
                    break;
                default:
                    $newColumn['renderer'] = "text";
                    $newColumn['editable'] = true;
                    break;
            }
            $result[] = $newColumn;
        }
        // dump($result);
        return $result;
    }

    public function render()
    {
        $colName = $this->colName;
        $modelPath = $this->modelPath;
        $type = $this->type;
        $id = $this->id;

        $superProps = SuperProps::getFor($this->type);
        $props = $superProps['props']["_" . $colName];
        // dump($colName);
        // dump($props);

        $renderer_edit = $props['relationships']['renderer_edit'];
        $showAll = $renderer_edit === "many_icons";
        $smallModel = $props['relationships']['eloquentParams'][1];
        $instance = new $smallModel;

        $fn = $props['relationships']['renderer_edit_param'];
        if (!method_exists($instance, $fn))  $fn = '';
        $tableName = $smallModel::getTableName();
        $columns = ($fn === '')
            ? [
                ["dataIndex" => 'id', "renderer" => "id", "type" => $tableName, "align" => "center"],
                ["dataIndex" => 'name'],
            ]
            : $instance->$fn();

        $row = $modelPath::find($id);
        $dataSource = $row ? $this->getDataSource($row, $colName, $showAll) : [];
        switch ($renderer_edit) {
            case "many_icons":
                $colSpan =  Helper::getColSpan($colName, $type);
                foreach ($dataSource as &$item) {
                    $item['href'] = route($tableName . '.edit', $item->id);
                    $item['gray'] = $item['resigned'];
                }
                $dataSource = $dataSource->all(); // Force LengthAwarePaginator to Array
                return view('components.controls.many-icon-params')->with(compact('dataSource', 'colSpan'));
            case "many_lines":
                $tableName =  $smallModel::getTableName();
                $sp = SuperProps::getFor($tableName);
                return view('components.controls.many-line-params', [
                    'dataSource' => $dataSource,
                    'fn' => $fn,
                    'readOnlyColumns' => $this->makeReadOnlyColumns($columns, $sp, $tableName),
                    'editableColumns' => $this->makeEditableColumns($columns, $sp, $tableName),
                    'tableName' => $smallModel::getTableName(),
                    'table01Name' => $this->table01Name,
                ]);
            default:
                return "Unknown renderer_edit [$renderer_edit] in Relationship Screen, pls select ManyIcons or ManyLines";
        }
    }
}
