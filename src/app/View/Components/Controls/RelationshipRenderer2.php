<?php

namespace App\View\Components\Controls;

use App\Http\Controllers\Workflow\LibEditableTables;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\SuperProps;
use App\View\Components\Controls\RelationshipRenderer\TraitTableColumnEditable;
use App\View\Components\Controls\RelationshipRenderer\TraitTableColumnEditable2ndThead;
use App\View\Components\Controls\RelationshipRenderer\TraitTableColumnRO;
use App\View\Components\Controls\RelationshipRenderer\TraitTableEditableDataSourceWithOld;

use App\View\Components\Controls\RelationshipRenderer\TraitTableRendererSameAsViewAll;
use App\View\Components\Controls\RelationshipRenderer\TraitTableRendererManyIcons;
use App\View\Components\Controls\RelationshipRenderer\TraitTableRendererManyLines;
use App\View\Components\Controls\RelationshipRenderer\TraitTableRendererCalendarGrid;
use App\View\Components\Controls\RelationshipRenderer\TraitTableRendererManyCheckpoints;
use App\View\Components\Controls\RelationshipRenderer\TraitTableRendererManyToManyMatrix;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class RelationshipRenderer2 extends Component
{
    use TraitTableColumnRO;
    use TraitTableColumnEditable;
    use TraitTableColumnEditable2ndThead;
    use TraitTableEditableDataSourceWithOld;

    use TraitTableRendererSameAsViewAll;
    use TraitTableRendererManyIcons;
    use TraitTableRendererManyLines;
    use TraitTableRendererManyToManyMatrix;
    use TraitTableRendererManyCheckpoints;
    use TraitTableRendererCalendarGrid;

    private static $table00Count = 1;

    private $table01Name;
    private $tableDebug = false;

    private $entityId, $entityType;

    private $tablesHaveCreateANewForm;
    private $tablesInEditableMode;
    // private $tablesCallCmdBtn;
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
        private $numberOfEmptyLines = 0,
    ) {
        $this->table01Name = "table" . str_pad(static::$table00Count++, 2, 0, STR_PAD_LEFT);
        $this->entityId = CurrentRoute::getEntityId($this->type);
        $this->entityType = Str::modelPathFrom($this->type);
        // dump($this->table01Name);
        // dump($item);

        $this->tablesHaveCreateANewForm = config()->get('tablesHaveCreateANewForm');
        $this->tablesInEditableMode = LibEditableTables::getAllIndexed();
    }

    private function isTableOrderable($row, $colName, $columns)
    {
        $eloquentParam = $row::$eloquentParams[$colName];
        //TODO: This is to prevent from a crash
        if ($eloquentParam[0] === 'morphToMany') return [];

        $dummyInstance = new $eloquentParam[1];
        $fillable = $dummyInstance->getFillable();
        $hasOrderNoColumnInFillable = in_array('order_no', $fillable);

        // Log::info($columns);
        $allDataIndex = array_map(fn($c) => $c['dataIndex'] ?? "", $columns);
        $hasOrderNoColumnInManyLineParams = false !== array_search('order_no', $allDataIndex);
        // dump($columns, $hasOrderNoColumnInManyLineParams);

        // if (!$hasOrderNoColumn) dump("Order_no column not found, re-ordering function will not work");
        return $hasOrderNoColumnInFillable && $hasOrderNoColumnInManyLineParams;
    }

    private function getPaginatedDataSource($row, $colName, $isOrderable, $props, $showAll = false)
    {
        if (!isset($row::$eloquentParams[$colName])) {
            //TODO: 
            dump("Not found $colName, maybe change to dropdown?");
            dump($row::$eloquentParams);
            return [];
        } else {
            $eloquentParam = $row::$eloquentParams[$colName];
            //TODO: This is to prevent from a crash
            if ($eloquentParam[0] === 'morphToMany') return [];

            $filterColumns = $props['relationships']['filter_columns'] ?? [];
            $filterOperator = $props['relationships']['filter_operator'];
            $filterValues = $props['relationships']['filter_values'] ?? [];

            // Log::info($filterColumns);
            // Log::info($filterOperator);
            // Log::info($filterValues);

            if (isset($eloquentParam[2])) $relation = $row->{$eloquentParam[0]}($eloquentParam[1], $eloquentParam[2]);
            elseif (isset($eloquentParam[1])) $relation = $row->{$eloquentParam[0]}($eloquentParam[1]);
            elseif (isset($eloquentParam[0])) $relation = $row->{$eloquentParam[0]}();
            $perPage = $showAll ? 10000 : 25;
            $result = $relation->getQuery();
            if (sizeof($filterColumns)) {
                foreach ($filterColumns as $key => $value) {
                    switch ($filterOperator) {
                        case '=':
                            if (isset($filterValues[$key])) $result = $result->where($value, $filterValues[$key]);
                            break;
                        case '!=':
                            if (isset($filterValues[$key])) $result = $result->whereNot($value, $filterValues[$key]);
                            break;
                        case 'is_null':
                            $result = $result->whereNull($value);
                            break;
                        case 'is_not_null':
                            $result = $result->whereNotNull($value);
                            break;
                        default:
                            dump("Unknown filter operator [$filterOperator]");
                            break;
                    }
                }
            }
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
        // if (!$instance::$nameless) $defaultColumns[] = ["dataIndex" => 'name',];
        if (!$instance::$nameless) {
            $defaultColumns[] = ["dataIndex" => 'name',];
            $defaultColumns[] = ["dataIndex" => 'description',];
        }
        $columns = ($fn === '')
            ? $defaultColumns
            : $instance->$fn($this->item);

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

        $editable = isset($this->tablesInEditableMode[$this->type]) && in_array($tableName, array_keys($this->tablesInEditableMode[$this->type]));
        $showAll = (
            $renderer_edit === "many_icons" ||
            $renderer_edit === "many_checkpoints" ||
            ($renderer_edit === "many_lines" && $editable) ||
            $this->noCss
        );

        [$tableFooter, $columns,] = $this->loadColumnsFromRendererEditParam($props, $instance, $lineModelPath, $tableName);

        $row = $modelPath::find($id);
        // dump($row);
        $isOrderable = $row ? $this->isTableOrderable($row, $colName, $columns) : false;
        $paginatedDataSource = $row ? $this->getPaginatedDataSource($row, $colName, $isOrderable, $props, $showAll) : [];
        switch ($renderer_edit) {
            case "same_as_view_all":
                return $this->renderSameAsViewAll($props, $paginatedDataSource);
            case "many_icons":
                return $this->renderManyIcons($colName, $type, $paginatedDataSource, $tableName);
            case "calendar_grid":
                $action = CurrentRoute::getControllerAction();
                if (in_array($action, ['show', 'edit'])) {
                    // Log::info("Calendar Grid " . $action);
                    return $this->renderCalendarGrid($id, $modelPath, $row, $type, $action == 'show');
                }
                //fall down to many lines for print
            case "many_lines":
                return $this->renderManyLines($tableName, $paginatedDataSource, $lineModelPath, $columns, $editable, $instance, $isOrderable, $colName, $tableFooter, $this->numberOfEmptyLines);
            case "many_to_many_matrix":
                return $this->renderManyToManyMatrix($tableName, $paginatedDataSource, $lineModelPath, $columns, $editable, $instance, $isOrderable, $colName, $tableFooter, $this->item);
                // case "many_to_many_lines":
                // return $this-
            case "many_checkpoints":
                return $this->renderManyCheckpoints($tableName, $paginatedDataSource, $lineModelPath, $columns, $editable, $instance, $isOrderable, $colName, $tableFooter);
            default:
                return "Unknown renderer_edit [$renderer_edit] in Relationship Screen, pls select ManyIcons or ManyLines";
        }
    }
}
