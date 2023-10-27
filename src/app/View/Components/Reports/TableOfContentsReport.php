<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class TableOfContentsReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $routeName = '',
        private $dataSource = []
    ) {
        //
    }

    public function render()
    {
        $dataSource = $this->dataSource;
        return view('components.reports.table-of-contents-report', [
            'routeName' => $this->routeName,
            'dataSource' => $dataSource
        ]);
    }
}
