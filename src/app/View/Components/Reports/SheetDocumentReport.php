<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class SheetDocumentReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $columns = [],
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
        return view('components.reports.sheet-document-report', [
            'dataSource' => $this->dataSource,
            'columns' => $this->columns
        ]);
    }
}
