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

        private $actionBtnShowExportCsv = false,
        private $actionBtnShowPrintButton = false,
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
        $paramsRoute = [
            'groupBy' => $this->groupBy,
            'groupByLength' => $this->groupByLength,
        ];
        $routeExportCSV = route($this->type . '_mep.exportCSV2', $paramsRoute);
        return view('components.form.action-button-group-view-matrix', [
            'type' => $this->type,
            'routeExportCSV' => $routeExportCSV,

            'actionBtnShowExportCsv' => $this->actionBtnShowExportCsv,
            'actionBtnShowPrintButton' => $this->actionBtnShowPrintButton,
        ]);
    }
}
