<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilter;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Prod_routing_link;
use Illuminate\View\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ProdRoutingLinkFilter extends Component
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
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $readOnly = false,
        private $allowClear = false,
        private $typeToLoadListener = null, //<<Add this to load listenersOfDropdown2
    ) {
        // if (old($name)) $this->selected = old($name);
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    private function getDataSource()
    {
        $db = Prod_routing_link::select('id', 'name', 'description')
            ->orderBy('name')
            ->get();
        // Oracy::attach("getSubProjects()", $db);

        // $db = $db->filter(fn ($item) => $item->isShowOn(CurrentRoute::getTypePlural()))->values();

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
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
