<?php

namespace App\View\Components\Renderer\Report2;

use App\BigThink\HasShowOnScreens;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControlReport;
use App\Http\Controllers\Reports\TraitUserCompanyTree;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Prod_routing_link;
use App\Models\Qaqc_ncr;
use App\Models\Term;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use Illuminate\Support\Str;
use App\Utils\Support\ModelData;
use Illuminate\Support\Arr;
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
    protected $DEFECT_REPORT_TYPE_ID = 118;
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
        $this->id =  $listenReducer ? $listenReducer->column_name : $filter->name;

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

    private function getEntityData($db, $isBlackList, $bWListIds, $triggerName = null)
    {
        $query = $db->select('id', 'name', 'description');
        if ($triggerName) $query->addSelect($triggerName);
        return $query
            ->when($isBlackList, function ($query) use ($bWListIds) {
                return $query->whereIn('id', $bWListIds);
            }, function ($query) use ($bWListIds) {
                return $query->whereNotIn('id', $bWListIds);
            })
            ->orderBy('name')
            ->get();
    }

    private function handleDataSourceTypeID($entityType, $isBlackList, $bWListIds, $filter)
    {
        $modelClass = ModelData::initModelByField($entityType);
        if (!$modelClass) return [];
        $db = $modelClass::query();
        switch($entityType){
            case 'terms':
                $dataIndex = $filter->data_index;
                $filterId = 0;
                switch ($dataIndex) {
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
                $db = $modelClass::query()
                ->where('field_id', $filterId)
                ->get();
                return $db;

            case 'prod_routing_links':
                $db = $db->select('id', 'name', 'description', 'prod_discipline_id')
                    ->with('getProdRoutings')
                    ->orderBy('name')
                    ->get();
        
                $newDB = [];
                foreach ($db as $item) {
                    $i = (object)[];
                    $i->id = $item->id;
                    $i->name = $item->name;
                    $i->description = $item->description;
                    $i->prod_discipline_id = $item->prod_discipline_id;
        
                    $i->prod_routing_id = $item->getProdRoutings->pluck('id');
                    $newDB[] = $i;
                }
                return $newDB;
            case 'users':
                $treeData = $this->getDataByCompanyTree();
                $dataSource = [];
                $isAdmin = CurrentUser::isAdmin();
                foreach ($treeData as $value) {
                    $name = $value->resigned ? $value->name0 . ' (RESIGNED)' : $value->name0;
                    $name = $value->show_on_beta ? $name . ' (BETA)' : $name;
                    $addId = $isAdmin ? '(#' . $value->id . ')' : '';
                    $dataSource[] = [
                        'id' => $value->id,
                        'name' => $name . ' ' . $addId,
                        'department_id' => $value->department,
                        'workplace_id' => $value->workplace
                    ];
                }
                return collect($dataSource);
            case 'prod_routings':
                $newDB = $db->select('id', 'name', 'description')
                    ->when($isBlackList, fn($query) => $query->whereIn('id', $bWListIds), fn($query) => $query->whereNotIn('id', $bWListIds))
                    ->with('getSubProjects')
                    ->with('getScreensShowMeOn')
                    ->orderBy('name')
                    ->get();
                foreach ($newDB as &$item) {
                    $item->getSubProjects = $item->getSubProjects->pluck('id')->toArray();
                }
                return $newDB;
            }
        $triggerNames = explode(',', $this->filter->getListenReducer->triggers ?? '');
        foreach ($triggerNames as $triggerName) return $this->getEntityData($db, $isBlackList, $bWListIds, $triggerName);
    }

 
    public function render()
    {
        // dd($this->report);
        $this->renderJSForK();
        $params = $this->getParamsForHasDataSource();
        // dump($params);
        return view(
            'components.controls.has-data-source.dropdown2',
            $params
        );
    }
}
