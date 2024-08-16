<?php

namespace App\View\Components\Renderer\Report2;

use App\BigThink\HasShowOnScreens;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControlReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Prod_routing;
use App\Models\Prod_routing_link;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;
use App\Utils\Support\ModelData;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Component;

class FilterReportItem extends Component
{
    use HasShowOnScreens;
    use TraitListenerControlReport;

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

        $filter = $this->filter;


        $entityType = $filter->entity_type;
        $this->tableName = Str::plural($entityType);
        $this->id = $filter->id;
        $this->multiple = (bool)$filter->is_multiple;
        $this->name = $filter->is_multiple ? Str::plural($filter->data_index) : $filter->data_index;
        $this->allowClear = (bool)$filter->allow_clear;
    }

    private function getListenReducer()
    {
        return $this->filter->getListenReducer;
    }

    private function getDataSource()
    {
        $controlTypeId = $this->filter->control_type;
        $entityType = $this->filter->entity_type;
        $table = Str::plural($this->filter->entity_type);

        switch($controlTypeId) {
            case 633:
                $modelClass = ModelData::initModelByField($entityType);    
                if ($modelClass) {
                    $db = $modelClass::query();
                    $listenReducer = $this->filter->getListenReducer;
                    $triggerName = $listenReducer->triggers ?? '';
                    if($triggerName){
                        $triggerNames = explode(',',$triggerName);
                        foreach ($triggerNames as $triggerName) {
                            if (!empty($triggerName) && Schema::hasColumn($table, $triggerName)) {
                                $db = $db->select('id', 'name', 'description', $triggerName)
                                ->orderBy('name')
                                ->get();
                                return $db;
                            } else{
                                switch($entityType){
                                    case 'prod_routings':
                                        $db = $modelClass::select('id', 'name', 'description')
                                        ->with('getSubProjects')
                                        ->with('getScreensShowMeOn')
                                        ->orderBy('name')
                                        ->get();
                                        foreach ($db as &$item) {
                                            $item->getSubProjects = $item->getSubProjects->pluck('id')->toArray();
                                        }
                                        return $db;
                                    case 'prod_routing_links':
                                        $db = Prod_routing_link::select('id', 'name', 'description', 'prod_discipline_id')
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
                                }
                            }
                        }
                    }else {
                        $db = $db->select('id', 'name', 'description')
                        ->orderBy('name')
                        ->get();
                        return $db;
                    }
                }
            case 631:
                $statuses = LibStatuses::getFor($entityType);
                $dt = array_map(fn($key, $status) => ['id' => $key, 'name' => $status['title']], array_keys($statuses), $statuses);
                return $dt;
        }
        
    }
    public function render()
    {
        $this->renderJSForK();
        $params = $this->getParamsForHasDataSource();
        // dump($params);
        return view(
            'components.controls.has-data-source.dropdown2',
            $params
        );
    }
}
