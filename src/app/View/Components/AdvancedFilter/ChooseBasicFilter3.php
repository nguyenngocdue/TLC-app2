<?php

namespace App\View\Components\AdvancedFilter;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Utils\ClassList;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ChooseBasicFilter3 extends Component
{
    use TraitViewAllFunctions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = null,
        private $name = null,
        private $selected = null,
        private $multiple = false,
        private $readOnly = false,
        private $saveOnChange = false,
        private $allowClear = false,
        // private $nameless = 'nameless',
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        [,,,,, $basicFilter, $chooseBasicFilter] = $this->getUserSettingsViewAll();
        $dataSource = $basicFilter ? array_keys($basicFilter) : [];
        array_unshift($dataSource, '');
        return view(
            'components.advanced-filter.choose-basic-filter3',
            [
                'name' => $this->name,
                'dataSource' => $dataSource,
                'valueControl' => $chooseBasicFilter,
                'multipleStr' => $this->multiple ? "multiple" : "",
                'classList' => ClassList::DROPDOWN,
            ]
        );
    }
}
