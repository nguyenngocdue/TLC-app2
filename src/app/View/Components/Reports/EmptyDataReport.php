<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;

class EmptyDataReport extends Component
{
    public function __construct(
        private $layout = ''
    ) {
    }

    public function render()
    {
        // dd($this->formName);
        return view('components.reports.empty-data-report', [
            'layout' => $this->layout
        ]);
    }
}
