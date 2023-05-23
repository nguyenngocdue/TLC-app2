<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAdvancedFilter;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;

class AdvancedFilter extends Component
{
    use TraitEntityAdvancedFilter;
    use TraitViewAllFunctions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = "",
        private $valueAdvanceFilters = null,
        private $currentFilter = null
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
        $propsFilters = $this->advanceFilter();
        [,,,,, $basicFilter, $chooseBasicFilter] = $this->getUserSettings();
        $count = count($propsFilters) ?? 0;
        $maxH = round($count / 4) * 3.7 . 'rem';
        return view('components.renderer.advanced-filter', [
            'type' => $this->type,
            'props' => $propsFilters,
            'valueAdvanceFilters' => $this->valueAdvanceFilters,
            'valueBasicFilter' => $chooseBasicFilter,
            'currentFilter' => $this->currentFilter,
            'basicFilter' => array_keys($basicFilter) ?? [],
            'maxH' => $maxH,
        ]);
    }
}
