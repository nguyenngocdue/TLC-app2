<?php

namespace App\View\Components\Renderer\Report2;

use App\BigThink\HasShowOnScreens;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControlReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Prod_routing_link;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;
use App\Utils\Support\ModelData;
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

    private function getDataSource()
    {
        $filter = $this->filter;
        $controlTypeId = $filter->control_type;
        $entityType = $filter->entity_type;
        $isBlackList = $filter->black_or_white;
        $bWListIds = explode(',', $filter->bw_list_ids);

        switch ($controlTypeId) {
            case 633:
                return $this->handleControlType633($entityType, $isBlackList, $bWListIds);

            case 631:
                return $this->getStatuses($entityType);

            default:
                return [];
        }
    }

    private function handleControlType633($entityType, $isBlackList, $bWListIds)
    {
        $modelClass = ModelData::initModelByField($entityType);
        if (!$modelClass) return [];

        $db = $modelClass::query();
        $triggerNames = explode(',', $this->filter->getListenReducer->triggers ?? '');

        if (!empty($triggerNames)) {
            foreach ($triggerNames as $triggerName) {
                if (Schema::hasColumn(Str::plural($entityType), $triggerName)) {
                    return $this->getFilteredData($db, $triggerName, $isBlackList, $bWListIds);
                }
            }
        }

        return $this->getEntityData($entityType, $db, $isBlackList, $bWListIds);
    }

    private function getFilteredData($db, $triggerName, $isBlackList, $bWListIds)
    {
        return $db->select('id', 'name', 'description', $triggerName)
            ->when($isBlackList, fn($query) => $query->whereIn('id', $bWListIds), fn($query) => $query->whereNotIn('id', $bWListIds))
            ->orderBy('name')
            ->get();
    }

    private function getEntityData($entityType, $db, $isBlackList, $bWListIds)
    {
        if ($entityType === 'prod_routings') {
            return $db->select('id', 'name', 'description')
                ->when($isBlackList, fn($query) => $query->whereIn('id', $bWListIds), fn($query) => $query->whereNotIn('id', $bWListIds))
                ->with(['getSubProjects', 'getScreensShowMeOn'])
                ->orderBy('name')
                ->get();
        }

        if ($entityType === 'prod_routing_links') {
            return Prod_routing_link::select('id', 'name', 'description', 'prod_discipline_id')
                ->when($isBlackList, fn($query) => $query->whereIn('id', $bWListIds), fn($query) => $query->whereNotIn('id', $bWListIds))
                ->with('getProdRoutings')
                ->orderBy('name')
                ->get()
                ->map(fn($item) => (object)[
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'prod_discipline_id' => $item->prod_discipline_id,
                    'prod_routing_id' => $item->getProdRoutings->pluck('id'),
                ]);
        }

        return $db->select('id', 'name', 'description')
            ->when($isBlackList, fn($query) => $query->whereIn('id', $bWListIds), fn($query) => $query->whereNotIn('id', $bWListIds))
            ->orderBy('name')
            ->get();
    }

    private function getStatuses($entityType)
    {
        return array_map(fn($key, $status) => ['id' => $key, 'name' => $status['title']], array_keys(LibStatuses::getFor($entityType)), LibStatuses::getFor($entityType));
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
