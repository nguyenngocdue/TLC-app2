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
        private $filterDetail,
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

        $filterDetail = $this->filterDetail;
        $column = $filterDetail->getColumn;

        $dataIndex = $column->data_index ?? '';
        $tableName = Str::plural(str_replace('_name', '', $dataIndex));
        $name = Str::singular(str_replace('_name', '_id', $dataIndex));

        $this->tableName = $tableName;
        $this->name = $name;
        $this->id = $filterDetail->id;
        $this->multiple = $filterDetail->is_multiple;
    }

    private function getListenReducer()
    {
        return $this->filterDetail->getListenReducer;
    }

    private function getDataSource()
    {

        $columnName = $this->filterDetail->getColumn->data_index;
        $modelClass = ModelData::initModelByField($columnName);
        $db = $modelClass::query();
        try {
            $listenReducer = $this->filterDetail->getListenReducer;
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
