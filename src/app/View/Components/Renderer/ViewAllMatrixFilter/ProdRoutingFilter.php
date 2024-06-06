<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilter;

use App\BigThink\Oracy;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Models\Prod_routing;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ProdRoutingFilter extends Component
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
        private $typePlural = null,
        private $selected = "",
        private $multiple = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $readOnly = false,
        private $allowClear = false,
        private $typeToLoadListener = null, //<<Add this to load listenersOfDropdown2
        private $dataSource = null,
    ) {
        // if (old($name)) $this->selected = old($name);
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
        if (is_null($this->typePlural)) $this->typePlural = CurrentRoute::getTypePlural();
    }

    private function getDataSource()
    {
        if ($this->dataSource) return $this->dataSource;
        $db = Prod_routing::select('id', 'name', 'description')
            ->with('getSubProjects')
            ->orderBy('name')
            ->get();
        // Oracy::attach("getSubProjects()", $db);

        // if (CurrentUser::isAdmin()) {
        $db = $db->filter(fn ($item) => $item->isShowOn($this->typePlural))->values();
        // }

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
