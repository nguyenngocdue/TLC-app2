<?php

namespace App\View\Components\Modals;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\User_team_ot;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class ParentType7UserOt extends Component
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
        private $typeToLoadListener = null,
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        $list = User_team_ot::get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataSource = $this->getDataSource();
        $selectedFirst = $dataSource[0]['id'];
        // dump($dataSource);
        $params = $this->getParamsForHasDataSource();
        $params['selected'] = "[$selectedFirst]";
        $this->renderJSForK();
        // dump($params);
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
