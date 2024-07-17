<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class TableReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource = []
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
        return view('components.reports.table-report', [
            'dataSource' => $this->dataSource
        ]);
    }
}
