<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilter;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Prod_order;
use App\Models\Prod_routing_link;
use Illuminate\View\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ProdOrderFilter extends Component
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
        $db = Prod_order::select('id', 'name', 'description', 'prod_routing_id', 'sub_project_id')
            // ->with('getProdRoutings')
            ->orderBy('name')
            ->get();

        $newDB = [];
        foreach ($db as $item) {
            $i = (object)[];
            $i->id = $item->id;
            $i->name = $item->name;
            $i->description = $item->description;
            $i->prod_routing_id = $item->prod_routing_id;
            $i->sub_project_id = $item->sub_project_id;
            // $i->prod_discipline_id = $item->prod_discipline_id;
            // $i->prod_routing_id = $item->getProdRoutings->pluck('id');
            $newDB[] = $i;
        }

        return $newDB;
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
