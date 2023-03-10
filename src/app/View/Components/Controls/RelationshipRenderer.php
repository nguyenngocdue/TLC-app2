<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use App\View\Components\Controls\RelationshipRenderer\TraitTableColumnEditable;
use App\View\Components\Controls\RelationshipRenderer\TraitTableColumnEditable2ndThead;
use App\View\Components\Controls\RelationshipRenderer\TraitTableColumnRO;
use App\View\Components\Controls\RelationshipRenderer\TraitTableEditableDataSourceWithOld;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class RelationshipRenderer extends Component
{
    use TraitTableColumnRO;
    use TraitTableColumnEditable;
    use TraitTableColumnEditable2ndThead;
    use TraitTableEditableDataSourceWithOld;

    private static $table00Count = 1;
    private $table01Name;
    private $tableDebug = false;

    private $tablesInEditableMode = [
        'hse_incident_reports' => ['hse_corrective_actions' => [],],
        'hr_overtime_requests' => ['hr_overtime_request_lines' => ['showBtnAddFromAList' => 1],],

        'zunit_test_07s' => ['prod_discipline_1s' => [],],

        'zunit_test_11s' => ['zunit_test_01s' => [],],
        'zunit_test_12s' => ['zunit_test_02s' => [],],
        'zunit_test_13s' => ['zunit_test_03s' => [],],
        'zunit_test_15s' => ['zunit_test_05s' => [],],
        'zunit_test_19s' => ['zunit_test_09s' => [],],
    ];
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
        private $noCss = false,
    ) {
        $this->table01Name = "table" . str_pad(static::$table00Count++, 2, 0, STR_PAD_LEFT);
        // dump($this->table01Name);
    }

    private function isTableOrderable($row, $colName, $columns)
    {
        $eloquentParam = $row->eloquentParams[$colName];
        //TODO: This is to prevent from a crash
        if ($eloquentParam[0] === 'morphToMany') return [];

        $dummyInstance = new $eloquentParam[1];
        $fillable = $dummyInstance->getFillable();
        $hasOrderNoColumnInFillable = in_array('order_no', $fillable);

        // Log::info($columns);
        $allDataIndex = array_map(fn ($c) => $c['dataIndex'] ?? "", $columns);
        $hasOrderNoColumnInManyLineParams = false !== array_search('order_no', $allDataIndex);
        // dump($columns, $hasOrderNoColumnInManyLineParams);

        // if (!$hasOrderNoColumn) dump("Order_no column not found, re-ordering function will not work");
        return $hasOrderNoColumnInFillable && $hasOrderNoColumnInManyLineParams;
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
            $perPage = $showAll ? 10000 : 25;
            $result = $relation->getQuery();
            if ($isOrderable) $result = $result->orderBy('order_no');
            $result = $result->paginate($perPage, ['*'], $colName);
            return $result;
        }
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
        $lineModelPath = $props['relationships']['eloquentParams'][1];
        $instance = new $lineModelPath;
        $tableName = $lineModelPath::getTableName();
        // dump($tableName);
        // dump($this->tablesInEditableMode[$this->type]);

        $editable = isset($this->tablesInEditableMode[$this->type]) && in_array($tableName, array_keys($this->tablesInEditableMode[$this->type]));
        $tableSettings = $editable ? $this->tablesInEditableMode[$this->type][$tableName] : [];
        $showAll = ($renderer_edit === "many_icons" || ($renderer_edit === "many_lines" && $editable));

        $tableFooter = "";
        $fn = $props['relationships']['renderer_edit_param'];
        if (!method_exists($instance, $fn)) {
            $tableFooter = "Function $fn() not found in $lineModelPath";
            $fn = '';
        }
        $defaultColumns = [
            ["dataIndex" => 'id', "renderer" => "id", "type" => $tableName, "align" => "center"],
        ];
        if (!$instance->nameless) $defaultColumns[] = ["dataIndex" => 'name',];
        $columns = ($fn === '')
            ? $defaultColumns
            : $instance->$fn();

        $row = $modelPath::find($id);
        $isOrderable = $row ? $this->isTableOrderable($row, $colName, $columns) : false;
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
                $dataSourceWithOld = $this->convertOldToDataSource($this->table01Name, $dataSource, $lineModelPath);
                $editableColumns = $this->makeEditableColumns($columns, $sp, $tableName, $this->table01Name);
                if ($editable) {
                    $this->alertIfFieldsAreMissingFromFillable($instance, $lineModelPath, $editableColumns);
                }
                //remakeOrderNoColumn MUST before attach Action Column
                $dataSourceWithOld = $this->remakeOrderNoColumn($dataSourceWithOld);
                $dataSourceWithOld = $this->attachActionColumn($this->table01Name, $dataSourceWithOld, $isOrderable);
                // dump($dataSourceWithOld);
                // dump($dataSource);
                // $tableName = $lineModelPath::getTableName();
                $roColumns = $this->makeReadOnlyColumns($columns, $sp, $tableName, $this->noCss);
                // dump($roColumns);
                return view('components.controls.many-line-params', [
                    'table01ROName' => $this->table01Name . "RO",
                    'readOnlyColumns' => $roColumns,
                    'dataSource' => $dataSource,

                    'isOrderable' => $isOrderable,
                    'table01Name' => $this->table01Name,
                    'editableColumns' => $editableColumns,
                    'dataSource2ndThead' => $this->makeEditable2ndThead($editableColumns, $this->table01Name),
                    'dataSourceWithOld' => $dataSourceWithOld,

                    'tableFooter' => $tableFooter,
                    'tableName' => $tableName,
                    'tableDebug' => $this->tableDebug ? true : false,
                    'tableDebugTextHidden' => $this->tableDebug ? "text" : "hidden",

                    'entityId' => CurrentRoute::getEntityId($this->type),
                    'entityType' => Str::modelPathFrom($this->type),
                    'userId' => CurrentUser::get()->id,
                    'editable' => $editable,
                    'noCss' => $this->noCss,
                    'tableSettings' => $tableSettings,
                ]);
            default:
                return "Unknown renderer_edit [$renderer_edit] in Relationship Screen, pls select ManyIcons or ManyLines";
        }
    }
}
