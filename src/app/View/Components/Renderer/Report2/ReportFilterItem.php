<?php

namespace App\View\Components\Renderer\Report2;

use App\BigThink\HasShowOnScreens;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControlReport;
use App\Http\Controllers\Reports\TraitUserCompanyTree;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Prod_discipline;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\DateReport;
use Illuminate\Support\Str;
use App\Utils\Support\ModelData;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\View\Component;

class ReportFilterItem extends Component
{
    use HasShowOnScreens;
    use TraitListenerControlReport;
    use TraitUserCompanyTree;

    protected $STATUS_TYPE_ID = 631;
    protected $MONTH_TYPE_ID = 632;
    protected $DATASOURCE_TYPE_ID = 633;
    protected $YEAR_TYPE_ID = 634;

    protected $DEFECT_ROOT_CAUSE_TYPE_ID = 117;
    protected $DEFECT_REPORT_TYPE_ID = 142;
    protected $INTER_SUBCON_TYPE_ID = 119;


    protected $BEGIN_YEAR = 2021;
    protected $END_YEAR = 2027;

    public function __construct(
        private $filter,
        private $id = "",
        private $name = "",
        private $tableName = "",
        private $selected = "",
        private $multiple = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $readOnly = false,
        private $allowClear = false,
        // private $typeToLoadListener = null, //<<Add this to load listenersOfDropdown2
        private $typePlural = null,
        private $dataSource = null,
    ) {
        // if (old($name)) $this->selected = old($name);
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
        if (is_null($this->typePlural)) $this->typePlural = CurrentRoute::getTypePlural();

        $listenReducer = $this->filter->getListenReducer;
        $this->id =  $listenReducer ? $this->filter->data_index : $filter->name;

        $entityType = $filter->entity_type;
        $this->tableName = Str::plural($entityType);
        $this->multiple = (bool)$filter->is_multiple;
        $this->name = $filter->is_multiple ? Str::plural($filter->data_index) : $filter->data_index;
        $this->allowClear = (bool)$filter->allow_clear;
    }

    private function getStatuses($entityType)
    {
        $lib = LibStatuses::getFor($entityType);
        return array_map(fn($key, $status) => ['id' => $key, 'name' => $status['title']], array_keys($lib), $lib);
    }

    private function getDataSource()
    {
        $filter = $this->filter;
        $controlTypeId = $filter->control_type;
        $entityType = $filter->entity_type;
        $isBlackList = $filter->black_or_white;
        $bWListIds = explode(',', $filter->bw_list_ids);

        switch ($controlTypeId) {
            case $this->DATASOURCE_TYPE_ID:
                return $this->handleDataSourceTypeID($entityType, $isBlackList, $bWListIds, $filter);
            case $this->STATUS_TYPE_ID:
                return $this->getStatuses($entityType);
            case $this->YEAR_TYPE_ID:
                return array_map(fn($item) => ['id' => $item, 'name' => (string)$item], range($this->BEGIN_YEAR, $this->END_YEAR));
            case $this->MONTH_TYPE_ID:
                $months = range(1, 12);
                sort($months);
                $months = array_map(
                    fn($item) => ['id' => $item, 'name' => DateReport::getMonthAbbreviation2($item)],
                    $months
                );
                return $months;
            default:
                return [];
        }
    }

    private function getDbQuery($dbQuery, $modelClass){        
        $fillable = $modelClass->getFillable();
        $eloquentParams = $modelClass::$eloquentParams;
        // Apply conditions based on the 'status' field if it exists.
        if (in_array('status', $fillable)) {
            // $dbQuery = $dbQuery->whereIn('status', ['manufacturing', 'construction_site', 'design']);
        }

        $relationships = [ 
            'getSubProjects','getScreensShowMeOn', 'getProdRoutingsOfSubProject'
        ];

        foreach ($relationships as $relation) {
            $table = $modelClass->getTable();
            if ($table =='prod_routing_links' && $relation == 'getScreensShowMeOn') {
                continue;
            }
            if (isset($eloquentParams[$relation])) {
                // $dbQuery->whereHas($relation); // TOFIX
            }
        }
        // Apply the 'whereDoesntHave' condition if 'getScreensHideMeOn' is defined.
        if (isset($eloquentParams['getScreensHideMeOn'])) {
            $dbQuery->whereDoesntHave('getScreensHideMeOn');
        }

        return $dbQuery;
    }

    private function getEntityData($modelClass, $db, $isBlackList, $bWListIds)
    {
        $fields = ['id', 'name', 'description'];
        $existingFields = array_merge($this->getExistingSchemaFields($modelClass, $fields), ['id']);
        try {
            $dbQuery = $db->select($existingFields);
            $dbQuery = $this->getDbQuery($dbQuery, $modelClass);
            $dbQuery = $dbQuery->when(
                $isBlackList,
                fn($query) => $query->whereIn('id', $bWListIds),
                fn($query) => $query->whereNotIn('id', $bWListIds)
            )
            ->where('name','not like','%-- available%');
            $dbQuery = $dbQuery->orderBy('name')->get();
        } catch (Exception $e) {
            dump($e->getMessage());
        }
        return $dbQuery;
    }


    private function getExistingSchemaFields($modelClass, $fields)
    {
        $fillable = $modelClass->getFillable();
        $existingFields = array_merge(['id'],  array_intersect($fillable, $fields));
        return $existingFields;
    }

    private function cleanAndExplode($string)
    {
        return explode(',', str_replace(' ', '', $string));
    }

    private function handleDataSourceTypeID($entityType, $isBlackList, $bWListIds, $filter)
    {
        $singularEntityType = Str::singular($entityType);
        $listenReducer = $filter->getListenReducer;
        $modelClass = ModelData::initModelByField($entityType);
        if (!$modelClass) return [];

        $triggerNames = $listenReducer?->triggers;
        $db = $modelClass::query();
        if (is_null($triggerNames) && $singularEntityType != 'user') return $this->getEntityData($modelClass, $db, $isBlackList, $bWListIds);

        if ($singularEntityType == 'term') {
            $filterId = 0;
            switch ($filter->data_index) {
                case 'defect_root_cause_id':
                case 'defect_root_cause_ids':
                    $filterId = $this->DEFECT_ROOT_CAUSE_TYPE_ID;
                    break;
                case 'defect_report_type_id':
                case 'defect_report_type_ids':
                    $filterId = $this->DEFECT_REPORT_TYPE_ID;;
                    break;
                case 'inter_subcon_id':
                case 'inter_subcon_ids':
                    $filterId = $this->INTER_SUBCON_TYPE_ID;;
                    break;
                default:
                    $filterId = 0;
                    break;
            }
            $db->where('field_id', $filterId)->get();
            return $db;
        } elseif ($singularEntityType == 'user') {
            $listenToAttrs = explode(',', str_replace(' ', '', $listenReducer?->listen_to_attrs));
            $dbQuery = $db->select()
                ->when(
                    $isBlackList,
                    fn($query) => $query->whereIn('id', $bWListIds),
                    fn($query) => $query->whereNotIn('id', $bWListIds)
                )
                ->when(
                    App::isProduction(),
                    fn($query) => $query->where('show_on_beta', 0)
                )
                ->orderBy('name')
                ->get();
            $newDB = [];
            foreach ($dbQuery as $item) {
                $i = (object)[];
                $i->id = $item->id;
                $i->name = $item->name . " (#{$item->id})";
                $i->description = $item?->description;
                foreach ($listenToAttrs as $value) {
                    $i->{$value} = $item->{$value};
                }
                $newDB[] = $i;
            }
            return $newDB;
        } else {
            $triggers = $this->cleanAndExplode($listenReducer->triggers);
            $listenToAttrs = $this->cleanAndExplode($listenReducer->listen_to_attrs);
            $fields = array_merge(['name', 'description'], $triggers, $listenToAttrs);
            $existingFields = $this->getExistingSchemaFields($modelClass, $fields);
            $eagerLoadFields = [];
            foreach ($listenToAttrs as $value) {
                if (str_contains($value, 'get')) $eagerLoadFields[] = $value;
                else $existingFields[] = $value;
            }
            
            // Initialize the query builder.
            $dbQuery = $db->select();
            $dbQuery = $this->getDbQuery($dbQuery, $modelClass);
            
            // Apply blacklist or whitelist conditions and eager load fields.
            $dbQuery = $dbQuery->when(
                $isBlackList,
                fn($query) => $query->whereIn('id', $bWListIds),
                fn($query) => $query->whereNotIn('id', $bWListIds)
                )
                ->with($eagerLoadFields)
                ->orderBy('name')
                ->get();
                
                
            $newDB = [];
            foreach ($dbQuery as $item) {
                if (str_contains($item->name, 'available')) continue;
                $processedItem = (object)[];
                // Assign existing fields
                foreach ($existingFields as $field) $processedItem->$field = $item->$field;
                // Assign relationship data or direct attribute based on trigger
                foreach ($listenToAttrs as $key => $attr) {
                    if (str_contains($attr, 'get')) {
                        $processedItem->{$attr} = ($x = $item->$attr) ? $x->pluck('id')->toArray() : [];
                    } else $processedItem->{$triggers[$key]} = $item->$attr;
                }
                $newDB[] = $processedItem;
            }
            return $newDB;
        }
    }


    public function render()
    {
        $this->renderJSForK();
        $params = $this->getParamsForHasDataSource();
        // dump($params);
        return view(
            'components.controls.has-data-source.dropdown5',
            $params
        );
    }
}
