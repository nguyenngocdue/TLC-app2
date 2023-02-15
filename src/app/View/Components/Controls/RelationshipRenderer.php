<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\SuperProps;
use App\View\Components\Controls\RelationshipRenderer\TraitTableColumnEditable;
use App\View\Components\Controls\RelationshipRenderer\TraitTableColumnRO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class RelationshipRenderer extends Component
{
    use TraitTableColumnRO;
    use TraitTableColumnEditable;

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

    private function isTableOrderable($row, $colName)
    {
        $eloquentParam = $row->eloquentParams[$colName];
        //TODO: This is to prevent from a crash
        if ($eloquentParam[0] === 'morphToMany') return [];

        $dummyInstance = new $eloquentParam[1];
        $fillable = $dummyInstance->getFillable();
        $hasOrderNoColumn = in_array('order_no', $fillable);

        // if (!$hasOrderNoColumn) dump("Order_no column not found, re-ordering function will not work");
        return $hasOrderNoColumn;
    }

    private function getPaginatedDataSource($row, $colName, $isOrderable, $showAll = false)
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

            if (isset($eloquentParam[2])) $relation = $row->{$eloquentParam[0]}($eloquentParam[1], $eloquentParam[2]);
            elseif (isset($eloquentParam[1])) $relation = $row->{$eloquentParam[0]}($eloquentParam[1]);
            elseif (isset($eloquentParam[0])) $relation = $row->{$eloquentParam[0]}();
            $perPage = $showAll ? 10000 : 10;
            $result = $relation->getQuery();
            if ($isOrderable) $result = $result->orderBy('order_no');

            $result = $result->paginate($perPage, ['*'], $colName);
            return $result;
        }
    }


    private function attachActionColumn($table01Name, $dataSource, $isOrderable)
    {
        // dump($dataSource);
        foreach ($dataSource as &$row) {
            $id = $row->order_no;
            // dump($index);
            $type = $this->tableDebug ? "text" : "hidden";
            $output = "
            <input readonly name='{$table01Name}[finger_print][]' value='$id' type=$type class='w-10 bg-gray-300' />
            <input readonly name='{$table01Name}[DESTROY_THIS_LINE][]'  type=$type class='w-10 bg-gray-300' />
            <div class='whitespace-nowrap flex justify-center '>";
            if ($isOrderable) $output .= "<x-renderer.button size='xs' value='$table01Name' onClick='moveUpEditableTable({control:this, fingerPrint: $id})'><i class='fa fa-arrow-up'></i></x-renderer.button>
                 <x-renderer.button size='xs' value='$table01Name' onClick='moveDownEditableTable({control:this, fingerPrint: $id})'><i class='fa fa-arrow-down'></i></x-renderer.button>";
            // if ($isOrderable) $output .= "<x-renderer.button size='xs' value='$table01Name' onClick='moveUpEditableTable({control:this, fingerPrint: $id})'><i class='fa fa-arrow-up'></i></x-renderer.button>
            //      <x-renderer.button size='xs' value='$table01Name' onClick='moveDownEditableTable({control:this, fingerPrint: $id})'><i class='fa fa-arrow-down'></i></x-renderer.button>";
            $output .= "<x-renderer.button size='xs' value='$table01Name' onClick='duplicateEditableTable({control:this, fingerPrint: $id})' type='secondary' ><i class='fa fa-copy'></i></x-renderer.button>
                <x-renderer.button size='xs' value='$table01Name' onClick='trashEditableTable({control:this, fingerPrint: $id})' type='danger' ><i class='fa fa-trash'></i></x-renderer.button>
            </div>
            ";
            $row->action = Blade::render($output);
        }
        return $dataSource;
    }

    private function parseHTTPArrayToLines(array $dataSource)
    {
        $result = [];
        foreach ($dataSource as $fieldName => $fieldValueArray) {
            foreach ($fieldValueArray as $key => $value) {
                $result[$key][$fieldName] = $value;
            }
        }
        return $result;
    }

    private function convertOldToDataSource($old, $dataSource, $lineModelPath)
    {
        if (is_null($old)) return $dataSource;
        $oldObjects = $this->parseHTTPArrayToLines($old);
        $result = new Collection();
        foreach ($oldObjects as $oldObject) {
            $result->add(new $lineModelPath($oldObject));
        }

        return $result;
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
        $lineModelPath = $props['relationships']['eloquentParams'][1];
        $instance = new $lineModelPath;

        $tableFooter = "";
        $fn = $props['relationships']['renderer_edit_param'];
        if (!method_exists($instance, $fn)) {
            $tableFooter = "Not found $fn in $lineModelPath";
            $fn = '';
        }
        $tableName = $lineModelPath::getTableName();
        $columns = ($fn === '')
            ? [
                ["dataIndex" => 'id', "renderer" => "id", "type" => $tableName, "align" => "center"],
                ["dataIndex" => 'name'],
            ]
            : $instance->$fn();

        $row = $modelPath::find($id);
        $isOrderable = $row ? $this->isTableOrderable($row, $colName,) : [];
        $dataSource = $row ? $this->getPaginatedDataSource($row, $colName, $isOrderable, $showAll) : [];
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
                $sp = SuperProps::getFor($tableName);
                $dataSourceWithOld = $this->convertOldToDataSource(old($this->table01Name), $dataSource, $lineModelPath);

                //remakeOrderNoColumn MUST before attach Action Column
                $dataSourceWithOld = $this->remakeOrderNoColumn($dataSourceWithOld);
                $dataSourceWithOld = $this->attachActionColumn($this->table01Name, $dataSourceWithOld, $isOrderable);
                // dump($dataSourceWithOld);
                return view('components.controls.many-line-params', [
                    'dataSource' => $dataSource,
                    'dataSourceWithOld' => $dataSourceWithOld,
                    'tableFooter' => $tableFooter,
                    'readOnlyColumns' => $this->makeReadOnlyColumns($columns, $sp, $tableName),
                    'editableColumns' => $this->makeEditableColumns($columns, $sp, $tableName, $this->table01Name),
                    'tableName' => $lineModelPath::getTableName(),
                    'table01Name' => $this->table01Name,
                    'table01ROName' => $this->table01Name . "RO",
                    'tableDebug' => $this->tableDebug ? "true" : "false",
                    'tableDebugTextHidden' => $this->tableDebug ? "text" : "hidden",
                    'entityId' => CurrentRoute::getEntityId($this->type),
                    'entityType' => Str::modelPathFrom($this->type),
                ]);
            default:
                return "Unknown renderer_edit [$renderer_edit] in Relationship Screen, pls select ManyIcons or ManyLines";
        }
    }
}
