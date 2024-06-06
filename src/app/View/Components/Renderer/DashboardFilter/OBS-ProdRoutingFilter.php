<?php

namespace App\View\Components\Renderer\DashboardFilter;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitGetSuffixListenerControl;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class ProdRoutingFilter extends Component
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
        private $dataSource = null,
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        $db = $this->dataSource->with("getSubProjects")->get();
        // Oracy::attach("getSubProjects()", $db);
        return $db;
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
        echo "Here";
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
