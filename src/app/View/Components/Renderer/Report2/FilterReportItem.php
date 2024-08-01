<?php

namespace App\View\Components\Renderer\Report2;

use App\BigThink\HasShowOnScreens;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControlReport;
use App\Models\Project;
use App\Models\Sub_project;
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
        $listenReducer = $filterDetail->getListenReducer;

        $dataIndex = $column->data_index ?? '';
        $tableName = Str::plural(str_replace('_name', '', $dataIndex));
        $name = Str::singular(str_replace('_name', '_id', $dataIndex));

        $this->tableName = $tableName;
        $this->name = $name;
        $this->id = $filterDetail->id;
    }

    private function getListenReducer()
    {
        return $this->filterDetail->getListenReducer;
    }

    private function getDataSource()
    {
        $listenReducer = $this->getListenReducer();
        // if (!isset($listenReducer->column_name)) return collect();
        try {
            $columnName = $listenReducer->column_name;
            $modelClass = ModelData::initModelByField($columnName);
            $db = $modelClass::query();
            $db = $db->select('id', 'name', 'description')
                ->orderBy('name')
                ->get();
        } catch (Exception $e) {
            return collect();
        }
        return $db;
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
