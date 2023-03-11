<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class ResetParamReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $typeReport = '',
        private $route = '',
        private $pageLimit = '',
        private $entity = '',
        private $modeNames = []
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
        return view('components.reports.reset-param-report', [
            'typeReport' => $this->typeReport,
            'route' => $this->route,
            'pageLimit' => $this->pageLimit,
            'entity' => $this->entity,
            'modeNames' => $this->modeNames
        ]);
    }
}
