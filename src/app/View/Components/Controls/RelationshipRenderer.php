<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class RelationshipRenderer extends Component
{
    private static $table00Count = 1;
    private $table01Name;
    private $tableDebug = false;
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
        if (!isset($row->eloquentParams[$colName])) {
            //TODO: 
            dump("Not found $colName, maybe change to dropdown?");
            dump($row->eloquentParams);
            return [];
        } else {
            $eloquentParam = $row->eloquentParams[$colName];
            //TODO: This is to prevent from a crash
            if ($eloquentParam[0] === 'morphToMany') return [];

            $dummyInstance = new $eloquentParam[1];
            $fillable = $dummyInstance->getFillable();
            $hasOrderNoColumn = in_array('order_no', $fillable);
            if (!$hasOrderNoColumn) dump("Order_no column not found, re-ordering function will not work");
            // dump($fillable);

            if (isset($eloquentParam[2])) $relation = $row->{$eloquentParam[0]}($eloquentParam[1], $eloquentParam[2]);
            elseif (isset($eloquentParam[1])) $relation = $row->{$eloquentParam[0]}($eloquentParam[1]);
            elseif (isset($eloquentParam[0])) $relation = $row->{$eloquentParam[0]}();
            $perPage = $showAll ? 10000 : 10;
            $result = $relation->getQuery();
            if ($hasOrderNoColumn) $result = $result->orderBy('order_no');

            $result = $result->paginate($perPage, ['*'], $colName);
            return $result;
        }
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

    private function makeEditableColumns($columns, $sp, $tableName, $table01Name)
    {
        $result = [
            [
                'dataIndex' => 'action',
            ]
        ];
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
                    $newColumn['cbbDataSourceObject'] = LibStatuses::getFor($tableName);
                    $newColumn['cbbDataSource'] = array_keys(LibStatuses::getFor($tableName));
                    $newColumn['renderer'] = 'dropdown';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = "block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 text-left placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm";
                    break;
                case 'dropdown':
                    $newColumn['renderer'] = 'dropdown4';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = "block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 text-left placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm";

                    $newColumn['type'] = $this->type;
                    $newColumn['lineType'] = Str::singular($tableName);
                    $newColumn['table'] = $prop['relationships']['table'];
                    $newColumn['table01Name'] = $table01Name;
                    // dump($newColumn);
                    break;
                case 'textarea':
                    $newColumn['renderer'] = 'textarea';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = "bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input";
                    break;
                case 'number':
                    $newColumn['renderer'] = 'number';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = "text-right block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm";
                    break;
                default:
                    $newColumn['renderer'] = "text";
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = " block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm";
                    break;
            }
            if ($newColumn['dataIndex'] === 'order_no') {
                $newColumn['onChange'] = "rerenderTableBaseOnNewOrder(`" . $table01Name . "`)";
            }
            $result[] = $newColumn;
        }
        // dump($result);
        return $result;
    }

    private function attachActionColumn($table01Name, $dataSource)
    {
        // dump($dataSource);
        foreach ($dataSource as &$row) {
            $id = $row->order_no;
            // dump($index);
            $type = $this->tableDebug ? "text" : "hidden";
            $row->action = Blade::render("
            <input readonly name='{$table01Name}[finger_print][]' value='$id' type=$type class='w-10 bg-gray-300' />
            <input readonly name='{$table01Name}[DESTROY_THIS_LINE][]'  type=$type class='w-10 bg-gray-300' />
            <div class='whitespace-nowrap flex'>
                <x-renderer.button size='xs' value='$table01Name' onClick='moveUpEditableTable({control:this, fingerPrint: $id})'><i class='fa fa-arrow-up'></i></x-renderer.button>
                <x-renderer.button size='xs' value='$table01Name' onClick='moveDownEditableTable({control:this, fingerPrint: $id})'><i class='fa fa-arrow-down'></i></x-renderer.button>
                <x-renderer.button size='xs' value='$table01Name' onClick='duplicateEditableTable({control:this, fingerPrint: $id})' type='secondary' ><i class='fa fa-copy'></i></x-renderer.button>
                <x-renderer.button size='xs' value='$table01Name' onClick='trashEditableTable({control:this, fingerPrint: $id})' type='danger' ><i class='fa fa-trash'></i></x-renderer.button>
            </div>
            ");
        }
        return $dataSource;
    }

    private function remakeOrderNoColumn($dataSource)
    {
        // dump($dataSource);
        foreach ($dataSource as $index => &$row) {
            $row->order_no = 1000 + $index * 10;
        }
        return $dataSource;
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

        $tableFooter = "";
        $fn = $props['relationships']['renderer_edit_param'];
        if (!method_exists($instance, $fn)) {
            $tableFooter = "Not found $fn in $smallModel";
            $fn = '';
        }
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
                //remakeOrderNoColumn MUST before attach Action Column
                $dataSource = $this->remakeOrderNoColumn($dataSource);
                $dataSource = $this->attachActionColumn($this->table01Name, $dataSource);
                return view('components.controls.many-line-params', [
                    'dataSource' => $dataSource,
                    'tableFooter' => $tableFooter,
                    'readOnlyColumns' => $this->makeReadOnlyColumns($columns, $sp, $tableName),
                    'editableColumns' => $this->makeEditableColumns($columns, $sp, $tableName, $this->table01Name),
                    'tableName' => $smallModel::getTableName(),
                    'table01Name' => $this->table01Name,
                    'table01ROName' => $this->table01Name . "RO",
                    'tableDebug' => $this->tableDebug ? "true" : "false",
                    'tableDebugTextHidden' => $this->tableDebug ? "text" : "hidden",
                    'entityId' => CurrentRoute::getEntityId($this->type),
                ]);
            default:
                return "Unknown renderer_edit [$renderer_edit] in Relationship Screen, pls select ManyIcons or ManyLines";
        }
    }
}
