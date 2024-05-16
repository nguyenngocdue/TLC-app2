<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilter;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitGetSuffixListenerControl;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Sub_project;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class SubProjectFilter extends Component
{
    use TraitListenerControl;
    use TraitGetSuffixListenerControl;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id,
        private $name,
        private $tableName,
        private $selected = "",
        private $multiple = false,
        private $readOnly = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $allowClear = false,
        private $typeToLoadListener = null, //<<Add this to load listenersOfDropdown2
        private $dataSource = null,
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        if ($this->dataSource) return $this->dataSource;
        $statuses = config("project.active_statuses.sub_projects");
        $dataSource = Sub_project::select('id', 'name', 'description', 'project_id', 'lod_id')
            ->whereIn('status', $statuses)
            ->orderBy('name')
            ->get();
        return $dataSource;
    }

    // public function getSuffix()
    // {
    //     return "_11111";
    // }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->renderJSForK();
        $params = $this->getParamsForHasDataSource();
        // dump($params);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
