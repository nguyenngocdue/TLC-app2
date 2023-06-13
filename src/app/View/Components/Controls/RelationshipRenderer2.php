<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\SuperProps;
use App\View\Components\Controls\RelationshipRenderer\TraitTableColumnEditable;
use App\View\Components\Controls\RelationshipRenderer\TraitTableColumnEditable2ndThead;
use App\View\Components\Controls\RelationshipRenderer\TraitTableColumnRO;
use App\View\Components\Controls\RelationshipRenderer\TraitTableEditableDataSourceWithOld;
use Carbon\Carbon;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class RelationshipRenderer2 extends Component
{
    use TraitTableColumnRO;
    use TraitTableColumnEditable;
    use TraitTableColumnEditable2ndThead;
    use TraitTableEditableDataSourceWithOld;

    private static $table00Count = 1;
    private $table01Name;
    private $tableDebug = false;

    private $entityId, $entityType;

    private $tablesHaveCreateANewForm;
    private $tablesInEditableMode;
    private $tablesCallCmdBtn;
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
        private $item = null,
        private $readOnly = false,
    ) {
        $this->table01Name = "table" . str_pad(static::$table00Count++, 2, 0, STR_PAD_LEFT);
        $this->entityId = CurrentRoute::getEntityId($this->type);
        $this->entityType = Str::modelPathFrom($this->type);
        // dump($this->table01Name);
        // dump($item);

        $this->tablesHaveCreateANewForm = config()->get('tablesHaveCreateANewForm');
        $this->tablesInEditableMode = config()->get('tablesInEditableMode');
        $this->tablesCallCmdBtn = config()->get('tablesCallCmdBtn');
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

    private function getValueOfComplexEloquent($name, $item)
    {
        switch ($name) {
            case 'fromChklstLine2Project':
                return  $item->getProject->id;
            case 'fromChklstLine2SubProject':
                return  $item->getSubProject->id;
            case 'fromChklstLine2Routing':
                return  $item->getProdRouting->id;
            case 'fromChklstLine2ProdOrder':
                return  $item->getProdOrder->id;
            default:
                return '';
        }
    }

    private function createHref($item, $createSettings, $tableName)
    {
        $itemOriginal = $item->getOriginal();

        // dump($createLink);
        $params = [];
        foreach ($createSettings as $key => $valueType) {
            if (isset($valueType['type'])) {
                switch ($valueType['type']) {
                    case 'formValue':
                        if ($valueType['name'] == 'entityParentType') $value = $this->entityType;
                        if ($valueType['name'] == 'entityParentId') $value = $this->entityId;
                        break;
                    case 'complexEloquent':
                        $value = $this->getValueOfComplexEloquent($valueType['name'], $item);
                        break;
                }
            } else {
                $value = $itemOriginal[$key] ?? '';
            }
            $params[$key] = $value;
            // $params[] = "$key=$value";
        }
        // dump($itemOriginal, $createSettings);
        $result = route($tableName . ".create", $params);
        // $result =  route($tableName . ".create");
        // $result = $result . '?' . join("&", $params);
        return $result;
    }

    private function loadColumnsFromRendererEditParam($props, $instance, $lineModelPath, $tableName)
    {
        $tableFooter = "";
        $fn = $props['relationships']['renderer_edit_param'];
        //Most of the time, it would be getManyLineParams
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

        return [$tableFooter, $columns,];
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

        $createANewForm = isset($this->tablesHaveCreateANewForm[$this->type]);
        $createSettings = $createANewForm ? $this->tablesHaveCreateANewForm[$this->type][$tableName] : [];

        $btnCmd = isset($this->tablesCallCmdBtn[$this->type]);
        $btnCmdSettings = $btnCmd ? ($this->tablesCallCmdBtn[$this->type][$tableName] ?? []) : [];

        $editable = isset($this->tablesInEditableMode[$this->type]) && in_array($tableName, array_keys($this->tablesInEditableMode[$this->type]));
        $tableSettings = $editable ? $this->tablesInEditableMode[$this->type][$tableName] : [];
        $showAll = ($renderer_edit === "many_icons" || ($renderer_edit === "many_lines" && $editable));

        [$tableFooter, $columns,] = $this->loadColumnsFromRendererEditParam($props, $instance, $lineModelPath, $tableName);

        $row = $modelPath::find($id);
        $isOrderable = $row ? $this->isTableOrderable($row, $colName, $columns) : false;
        $dataSource = $row ? $this->getPaginatedDataSource($row, $colName, $isOrderable, $showAll) : [];
        switch ($renderer_edit) {
            case "calendar_grid":
                $arrHidden = [];
                $dateTime = Carbon::parse($row->week);
                $weekNumber = $dateTime->weekOfYear;
                if ($dateTime->day == 26) {
                    $dayOfWeek = $dateTime->dayOfWeek;
                    $arrHidden = DateTimeConcern::getDayHiddenForDayIndexWeek($dayOfWeek);
                } else {
                    $dateTimeDay25 = Carbon::createFromDate($dateTime->year, $dateTime->month, 25);
                    $weekOfDay25 = $dateTimeDay25->weekOfYear;
                    if ($weekOfDay25 == $weekNumber) {
                        $dayOfWeekStart = $dateTime->dayOfWeek;
                        $dayOfWeekEnd = $dateTimeDay25->dayOfWeek;
                        $arrHidden = DateTimeConcern::getDayHiddenForDayIndexWeek($dayOfWeekStart, $dayOfWeekEnd);
                    }
                }
                $index = strpos($type, "_");
                $type = substr($type, $index + 1);
                $apiUrl = route($type . '.index');
                $token = CurrentUser::getTokenForApi();
                return view('components.calendar.calendar-grid', [
                    'timesheetableType' => $modelPath,
                    'timesheetableId' => $id,
                    'apiUrl' => $apiUrl,
                    'readOnly' => $this->readOnly,
                    'arrHidden' => $arrHidden,
                ]);
            case "many_icons":
                $colSpan =  Helper::getColSpan($colName, $type);
                foreach ($dataSource as &$item) {
                    $item['href'] = route($tableName . '.edit', $item->id);
                    $item['gray'] = $item['resigned'];
                }
                $dataSource = $dataSource->all(); // Force LengthAwarePaginator to Array
                return view('components.controls.many-icon-params')->with(compact('dataSource', 'colSpan'));
                // case "calendar_grid":
            case "many_lines":
                $sp = SuperProps::getFor($tableName);
                $dataSourceWithOld = $this->convertOldToDataSource($this->table01Name, $dataSource, $lineModelPath);
                $editableColumns = $this->makeEditableColumns($columns, $sp, $tableName, $this->table01Name);
                if ($editable) {
                    $this->alertIfFieldsAreMissingFromFillable($instance, $lineModelPath, $editableColumns);
                }
                //remakeOrderNoColumn MUST before attach Action Column
                $dataSourceWithOld = $this->remakeOrderNoColumn($dataSourceWithOld);
                $dataSourceWithOld = $this->attachActionColumn($this->table01Name, $dataSourceWithOld, $isOrderable, $this->readOnly);
                // dump($dataSourceWithOld);
                // dump($dataSource);
                // $tableName = $lineModelPath::getTableName();
                $roColumns = $this->makeReadOnlyColumns($columns, $sp, $tableName, $this->noCss);
                // dump($roColumns);

                //<<This is to avoid crash in print mode, as no item pass down
                $href = '';
                if ($this->item) {
                    $itemOriginal = $this->item->getOriginal();
                    $href = $this->createHref($this->item, $createSettings, $tableName);
                }
                $view = $editable ? 'many-line-params-editable' : 'many-line-params-ro';
                $dataSource2ndThead = $this->readOnly ? null : $this->makeEditable2ndThead($editableColumns, $this->table01Name);
                if ($this->readOnly) {
                    array_shift($editableColumns);
                    foreach ($editableColumns as &$column) {
                        $column['properties']['readOnly'] = true;
                    }
                }

                return view('components.controls.' . $view, [
                    'readOnly' => $this->readOnly,
                    'table01ROName' => $this->table01Name . "RO",
                    'readOnlyColumns' => $roColumns,
                    'dataSource' => $dataSource,

                    'isOrderable' => $isOrderable,
                    'table01Name' => $this->table01Name,
                    'editableColumns' => $editableColumns,
                    'dataSource2ndThead' => $dataSource2ndThead,
                    'dataSourceWithOld' => $dataSourceWithOld,

                    'tableFooter' => $tableFooter,
                    'tableName' => $tableName,
                    'tableDebug' => $this->tableDebug ? true : false,
                    'tableDebugTextHidden' => $this->tableDebug ? "text" : "hidden",

                    'entityId' => $this->entityId,
                    'entityType' => $this->entityType,
                    'userId' => CurrentUser::get()->id,
                    'entityProjectId' => $itemOriginal['project_id'] ?? null,
                    'entitySubProjectId' => $itemOriginal['sub_project_id'] ?? null,

                    'noCss' => $this->noCss,

                    'editable' => $editable,
                    'tableSettings' => $tableSettings,

                    'createANewForm' => $createANewForm,
                    // 'createSettings' => $createSettings,
                    'btnCmdSettings' => $btnCmdSettings,
                    'href' => $href,
                    'showNo' => true,
                    'showNoR' => false,
                ]);
            default:
                return "Unknown renderer_edit [$renderer_edit] in Relationship Screen, pls select ManyIcons or ManyLines";
        }
    }
}
