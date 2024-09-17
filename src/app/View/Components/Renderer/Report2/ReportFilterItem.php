<?php

namespace App\View\Components\Renderer\Report2;

use App\BigThink\HasShowOnScreens;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControlReport;
use App\Http\Controllers\Reports\TraitUserCompanyTree;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Prod_routing_link;
use App\Models\Qaqc_ncr;
use App\Models\Term;
use App\Models\User;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use Illuminate\Support\Str;
use App\Utils\Support\ModelData;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
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
        return array_map(fn($key, $status) => [  'id' => $key, 'name' => $status['title']], array_keys($lib), $lib);
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
                return array_map(fn($item) => ['id' => $item, 'name'=> (string)$item], range($this->BEGIN_YEAR, $this->END_YEAR));    
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

    private function getEntityData($db, $isBlackList, $bWListIds)
    {
        $query = $db->select('id', 'name', 'description');
        return $query
            ->when($isBlackList, function ($query) use ($bWListIds) {
                return $query->whereIn('id', $bWListIds);
            }, function ($query) use ($bWListIds) {
                return $query->whereNotIn('id', $bWListIds);
            })
            ->orderBy('name')
            ->get();
    }


    private function getExistingSchemaFields($modelClass, $fields){
        $fillable= $modelClass->getFillable();
        $existingFields = array_merge(['id'],  array_intersect($fillable, $fields));
        return $existingFields;
    }

    private function cleanAndExplode($string) {
        return explode(',', str_replace(' ', '', $string));
    }
    
    private function handleDataSourceTypeID($entityType, $isBlackList, $bWListIds, $filter)
    {
        
        $listenReducer = $filter->getListenReducer;
        $modelClass = ModelData::initModelByField($entityType);
        if (!$modelClass) return [];
        
        $triggerNames = $listenReducer?->triggers;
        $db = $modelClass::query();
        if(is_null($triggerNames)) return $this->getEntityData($db, $isBlackList, $bWListIds);
        
        $singularEntityType = Str::singular($entityType);
        if ($singularEntityType == 'term') {
            $filterId = 0;
            switch ($filter->data_index) {
                case 'defect_root_cause_id':
                        $filterId = $this->DEFECT_ROOT_CAUSE_TYPE_ID;
                    break;
                case 'defect_report_type':
                        $filterId = $this->DEFECT_REPORT_TYPE_ID;;
                    break;
                case 'inter_subcon_id':
                        $filterId = $this->INTER_SUBCON_TYPE_ID;;
                    break;
            
                }
                $db->where('field_id', $filterId)->get();
                    return $db;
        }elseif ($singularEntityType == 'user') {
                $listenToAttrs = explode(',', str_replace(' ', '' ,$listenReducer->listen_to_attrs));
                $db = $db->get();
                $newDB = [];
                foreach ($db as $item) {
                    $i = (object)[];
                    $i->id = $item->id;
                    $i->name = $item->name;
                    $i->description = $item?->description;
                    foreach ($listenToAttrs as $value) {
                        $i->{$value} = $item->{$value};
                    }
                    $newDB[] = $i;
                }
                return $newDB;
        } 
        else {
            $triggers = $this->cleanAndExplode($listenReducer->triggers);
            $listenToAttrs = $this->cleanAndExplode($listenReducer->listen_to_attrs);
            
            $fields = array_merge(['name','description'], $triggers);
            $existingFields = $this->getExistingSchemaFields($modelClass, $fields);
            // dump($existingFields);
            
            $eagerLoadFields = [];
            foreach($listenToAttrs as $value) {
                if (str_contains($value, 'get')) $eagerLoadFields[] = $value;
                else $existingFields[] = $value;
            }
            // dump($existingFields);

            $dbQuery = $db->select(/* $existingFields */)
                        ->when($isBlackList, 
                            fn($query) => $query->whereIn('id', $bWListIds), 
                            fn($query) => $query->whereNotIn('id', $bWListIds)
                        )
                        ->with($eagerLoadFields)
                        ->orderBy('name')
                        ->get();

            if ($entityType == 'prod_orders') {
                // dd($db->select(array_merge($existingFields,['prod_routing_id']))->with(['getProdRouting'])->first()->toArray());
            }

            $newDB = [];
            foreach ($dbQuery as $item) {
                $processedItem = (object)[];
                // Assign existing fields
                foreach ($existingFields as $field) $processedItem->$field = $item->$field;
                // Assign relationship data or direct attribute based on trigger
                foreach ($listenToAttrs as $key => $attr) {
                    if (str_contains($attr, 'get')) {
                        // TO DEBUG
                        // if ($entityType == 'prod_orders') {
                        //     // dd($attr, $item);
                        // }
                        $processedItem->{$attr} = ($x = $item->$attr) ? $x->pluck('id')->toArray() : [];
                    } 
                    else $processedItem->{$triggers[$key]} = $item->$attr;
                }
                $newDB[] = $processedItem;
            }
            // TO DEBUG
            if ($entityType == 'prod_orders') {
                // dump($newDB);
            }

            return $newDB;
        } 
    }  

 
    public function render()
    {
        $this->renderJSForK();
        $params = $this->getParamsForHasDataSource();
        // dd($params);
        return view(
            'components.controls.has-data-source.dropdown2',
            $params
        );
    }
}
