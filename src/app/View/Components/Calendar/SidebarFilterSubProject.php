<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitGetSuffixListenerControl;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Sub_project;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class SidebarFilterSubProject extends Component
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
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    //This function is override from ModalFilterSubProject
    private function getDataSource()
    {
        // $hide_on_term_id = config("production.sub_projects.hr_timesheet_officer");
        // $dataSource = Sub_project::select('id', 'name', 'description', 'project_id', 'lod_id')
        //     // ->whereNot('hide_in_sts', 1)
        //     // ->whereDoesntHave("getScreensHideMeOn", fn($q) => $q->where('terms.id', $hide_on_term_id))
        //     ->orderBy('name')
        //     ->get();
        // return $dataSource;
        return [];
    }

    public function getSuffix()
    {
        return "_11111";
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
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
