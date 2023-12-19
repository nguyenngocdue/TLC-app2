<?php

namespace App\View\Components\Form;

use App\Http\Controllers\Workflow\LibApps;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class ActionButtonGroupViewMatrix extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = null,
        private $modelPath = null,
        private $groupBy = false,
        private $groupByLength = 1,
        private $routePrefix = '_route_prefix_params',
        private $params = [],

        protected $actionBtnList = [],
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
        $paramsRoute = http_build_query($this->params);
        // dump($paramsRoute);
        $routeExportCSV = route($this->type . $this->routePrefix, $paramsRoute);
        return view('components.form.action-button-group-view-matrix', [
            'type' => $this->type,
            'routeExportCSV' => $routeExportCSV,
            'actionBtnList' => $this->actionBtnList,
        ]);
    }
}
