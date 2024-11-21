<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class PerPageReport2 extends Component
{
    public function __construct(
        private $entityType = '',
        private $reportType2 = '',
        private $pageLimit = 10,
        private $route = '',
        private $reportId = null,
    ) {
    }

    public function render()
    {
        // dd($this->formName);
        return view('components.reports2.per-page-report2', [
            'entityType' => $this->entityType,
            'reportType2' => $this->reportType2,
            'pageLimit' => $this->pageLimit,
            'route' => $this->route,
            'rpId' => $this->reportId,
        ]);
    }
}
