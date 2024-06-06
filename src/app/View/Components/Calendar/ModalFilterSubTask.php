<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Pj_sub_task;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class ModalFilterSubTask extends Component
{
    use TraitListenerControl;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
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

    private function getDataSource()
    {
        $dataSource = Pj_sub_task::select('id', 'name', 'description')
            ->with("getParentTasks")
            ->get();
        // Oracy::attach("getParentTasks()", $dataSource);
        return $dataSource;
    }

    // private function getListenersOfDropdown2()
    // {
    //     $a = $this->getListeners2($this->typeToLoadListener);
    //     $a = array_values(array_filter($a, fn ($x) => $x['column_name'] == $this->name));
    //     foreach ($a[0]["triggers"] as &$x) $x .= "_1";
    //     $listenersOfDropdown2 = [$a[0]];
    //     return $listenersOfDropdown2;
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
