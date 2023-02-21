<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntitySuperPropsFilter;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;

class AdvancedFilter extends Component
{
    use TraitEntitySuperPropsFilter;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $type = "", private $valueAdvanceFilters = null)
    {
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
        // $valueAdvanceFilters = array_map(function ($item) {
        //     dump($item);
        //     return $item;
        // }, $this->valueAdvanceFilters);
        return view('components.renderer.advanced-filter', [
            'type' => $this->type,
            'props' => $propsFilters,
            'valueAdvanceFilters' => $this->valueAdvanceFilters,
        ]);
    }
}
