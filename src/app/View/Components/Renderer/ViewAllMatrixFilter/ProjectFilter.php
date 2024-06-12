<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilter;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Project;
use App\Utils\Support\CurrentRoute;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class ProjectFilter extends Component
{
    use TraitListenerControl;
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
    }

    private function getDataSource()
    {
        if ($this->dataSource) return $this->dataSource;
        // $statuses = config("project.active_statuses.projects");
        $db = Project::select('id', 'name', 'description')
            ->with('getScreensShowMeOn')
            ->orderBy('name')
            ->get();

        $db = $db->filter(fn ($item) => $item->isShowOn($this->typePlural))->values();
        return $db;
    }

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
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
