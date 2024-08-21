<?php

namespace App\View\Components\Renderer\Report2;

use App\BigThink\HasShowOnScreens;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitGetSuffixListenerControl;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControlReport;
use App\Models\Project;
use App\Models\Sub_project;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\ModelData;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use Illuminate\Support\Str;


class ReportFilterItemListener extends Component
{
    use TraitListenerControlReport;
    use TraitGetSuffixListenerControl;

    public function __construct(
        private $filterDetail,

        private $id = "sub_project_id",
        private $name = "sub_project_id",
        private $tableName = "sub_projects",
        private $selected = "",
        private $multiple = false,
        private $readOnly = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $allowClear = false,
        private $typeToLoadListener = null, //<<Add this to load listenersOfDropdown2
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getListenReducer()
    {
        return $this->filterDetail->getListenReducer;
    }

    private function getDataSource()
    {
        // $statuses = config("project.active_statuses.sub_projects");
        $listenReducer = $this->getListenReducer();
        if (!isset($listenReducer->column_name)) return collect();
        $columnName = $listenReducer->column_name;

        $modelClass = ModelData::initModelByField($columnName);
        $dataSource = $modelClass::select('id', 'name', 'description'/* , $listenReducer->triggers, 'lod_id' */)
            // ->whereIn('status', $statuses)
            ->orderBy('name');
        $dataSource =  $dataSource->get();
        dump($dataSource);
        return $dataSource;
    }
    public function render()
    {
        // dd($this->filterDetail);
        $this->renderJSForK();
        $params = $this->getParamsForHasDataSource();
        // dump($params);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
