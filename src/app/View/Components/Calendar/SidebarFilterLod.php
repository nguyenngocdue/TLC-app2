<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitGetSuffixListenerControl;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Pj_task_phase;
// use App\Models\Term;
// use Database\Seeders\FieldSeeder;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class SidebarFilterLod extends Component
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

    private function getDataSource()
    {
        //Needed to query the database
        // return [];

        $dataSource = Pj_task_phase::select('id', 'name', 'order_no', 'description')
            ->whereNotIn('id', [
                3, // Leave 
                4, //PH
            ])
            ->get();
        return $dataSource;
    }

    public function getSuffix()
    {
        return "_11111";
    }

    private function getListenersOfDropdown2()
    {
        $suffix = $this->getSuffix();
        return [
            [
                'listen_action' => 'dot',
                'column_name' => 'lod_id' . $suffix,
                'listen_to_attrs' => ['lod_id'],
                'listen_to_fields' => ['sub_project_id' . $suffix],
                'listen_to_tables' => ['sub_projects'],
                'table_name' => 'pj_task_phases',
                // 'table_name' => 'terms',
                'triggers' => ['sub_project_id' . $suffix],
            ],
        ];
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
