<?php

namespace App\View\Components\Utils;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Department;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class FilterHeadOfDepartment extends Component
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
        $departments =  Department::select('id', 'name', 'description')
            ->orderBy('name')
            ->get();
        $a = clone $departments[0];
        $a->name = 'All Company';
        $a->id = null;
        $departments = $departments->prepend($a);
        return $departments;
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
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
