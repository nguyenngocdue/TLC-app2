<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Pj_task_phase;
// use App\Models\Term;
// use Database\Seeders\FieldSeeder;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class ModalFilterLod extends Component
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
        private $readOnly = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $allowClear = false,
        private $typeToLoadListener = null, //<<Add this to load listenersOfDropdown2
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        // $field_id = FieldSeeder::getIdFromFieldName('getLodsOfTask');
        // $dataSource = Term::select('id', 'name', 'description')
        //     ->where('field_id', $field_id)
        //     ->whereNotIn('id', [221, 222])
        //     ->orderBy('name')
        //     ->get();
        $dataSource = Pj_task_phase::select('id', 'name', 'order_no', 'description')
            // ->where('show_in_task_budget', 1) //this also hide Overhead
            ->whereNotIn('id', [3, 4])
            ->get();
        return $dataSource;
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
