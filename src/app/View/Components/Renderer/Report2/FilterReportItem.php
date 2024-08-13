<?php

namespace App\View\Components\Renderer\Report2;

use App\BigThink\HasShowOnScreens;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControlReport;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;
use App\Utils\Support\ModelData;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class FilterReportItem extends Component
{
    use HasShowOnScreens;
    use TraitListenerControlReport;

    public function __construct(
        private $advancedFilter,
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

        $advFilter = $this->advancedFilter;

        $entityType = $advFilter->entity_type;
        $this->tableName = Str::plural($entityType);
        $this->id = $advFilter->id;
        $this->multiple = (bool)$advFilter->is_multiple;
        $this->name = $advFilter->is_multiple ? Str::plural($advFilter->data_index) : $advFilter->data_index;
        $this->allowClear = (bool)$advFilter->allow_clear;
    }

    private function getListenReducer()
    {
        return $this->advancedFilter->getListenReducer;
    }

    private function getDataSource()
    {
        $entityType = $this->advancedFilter->entity_type;
        $modelClass = ModelData::initModelByField($entityType);
        if ($modelClass) {
            $db = $modelClass::query();
            try {
                $listenReducer = $this->advancedFilter->getListenReducer;
                $triggerName = $listenReducer->triggers;
                $db = $db->select('id', 'name', 'description', $triggerName)
                    ->orderBy('name')
                    ->get();
            } catch (Exception $e) {
                $db = $db->select('id', 'name', 'description')
                    ->orderBy('name')
                    ->get();
            };
            return $db;
        }
        return collect();
    }
    public function render()
    {
        $this->renderJSForK();
        $params = $this->getParamsForHasDataSource();
        dump($params);
        return view(
            'components.controls.has-data-source.dropdown2',
            $params
        );
    }
}
