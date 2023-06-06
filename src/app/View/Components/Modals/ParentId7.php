<?php

namespace App\View\Components\Modals;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class ParentId7 extends Component
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
        private $typeToLoadListener = 'any_thing_but_not_null',

    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        return [
            ['id' => 2001, 'name' => 'B001', 'ot_team_id' => 1001],
            ['id' => 2002, 'name' => 'B002', 'ot_team_id' => 1002],
            ['id' => 2003, 'name' => 'B003', 'ot_team_id' => [1001, '1003-a']],
        ];
        // return $this->getAllIdMorphMany($attr_name);
    }

    private function getListenersOfDropdown2()
    {
        return [
            [
                'listen_action' => 'reduce',
                'column_name' => $this->name,
                'listen_to_attrs' => ['ot_team_id'],
                'listen_to_fields' => [$this->name],
                'listen_to_tables' => [$this->tableName],
                'table_name' => $this->tableName,
                // 'attrs_to_compare' => ['id'],
                'triggers' => ['ot_team_id'],
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
        $this->renderJSForK($this->tableName); //, 'modal_ot_team', $this->name);
        $params = $this->getParamsForHasDataSource();
        // dump($params);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
