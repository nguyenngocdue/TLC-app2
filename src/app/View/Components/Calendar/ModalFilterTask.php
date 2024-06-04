<?php

namespace App\View\Components\Calendar;

use App\BigThink\Oracy;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Pj_task;
use Illuminate\View\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ModalFilterTask extends Component
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
        $dataSource = Pj_task::select('id', 'name', 'description')
            ->with('getDisciplinesOfTask')
            ->with('getLodsOfTask')
            ->get();
        // Oracy::attach("getDisciplinesOfTask()", $dataSource);
        // Oracy::attach("getLodsOfTask()", $dataSource);
        return $dataSource;
    }

    // private function getListenersOfDropdown2()
    // {
    //     $a = $this->getListeners2($this->typeToLoadListener);
    //     $a = array_values(array_filter($a, fn ($x) => $x['column_name'] == $this->name));
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
        // Log::info($params);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
