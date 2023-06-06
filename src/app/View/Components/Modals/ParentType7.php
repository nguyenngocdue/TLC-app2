<?php

namespace App\View\Components\Modals;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class ParentType7 extends Component
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
        return [
            ['id' => 1001, 'name' => 'A001'],
            ['id' => 1002, 'name' => 'A002'],
            ['id' => '1003-a', 'name' => 'A003'],
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
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
