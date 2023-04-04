<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class PerPageReport extends Component
{
    public function __construct(
        private $typeReport = '',
        private $route = '',
        private $pageLimit = '',
        private $entity = '',
        private $routeName = '',
        private $formName = '',
    ) {
    }

    public function render()
    {
        // dd($this->formName);
        return view('components.reports.per-page-report', [
            'typeReport' => $this->typeReport,
            'route' => $this->route,
            'pageLimit' => $this->pageLimit,
            'entity' => $this->entity,
            'routeName' => $this->routeName,
            'formName' => $this->formName
        ]);
    }
}
