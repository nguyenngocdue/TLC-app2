<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Project;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class SidebarFilterProject extends Component
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
    ) {
        // if (old($name)) $this->selected = old($name);
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        // Needed to query the database
        // return [];
        $hide_on_term_id = config("production.projects.hr_timesheet_officer");
        return Project::select('id', 'name', 'description')
            ->whereDoesntHave("getScreensHideMeOn", fn($q) => $q->where('terms.id', $hide_on_term_id))
            ->orderBy('name')
            ->get();
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
