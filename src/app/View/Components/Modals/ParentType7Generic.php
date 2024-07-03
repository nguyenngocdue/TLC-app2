<?php

namespace App\View\Components\Modals;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use Illuminate\View\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ParentType7Generic extends Component
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

        private $dataSourceTableName = null,

    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        $modalPath = Str::modelPathFrom($this->dataSourceTableName);
        return $modalPath::all();
        // return [
        //     ['id' => 1, 'name' => 'Hello 1'],
        //     ['id' => 2, 'name' => 'Hello 2'],
        // ];
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
