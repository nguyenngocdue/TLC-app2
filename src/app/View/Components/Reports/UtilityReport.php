<?php

namespace App\View\Components\Reports;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class UtilityReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $routeName = ''
    ) {
        //
    }

    public function render()
    {
        return view('components.reports.utility-report', [
            'routeName' => $this->routeName,
        ]);
    }
}
