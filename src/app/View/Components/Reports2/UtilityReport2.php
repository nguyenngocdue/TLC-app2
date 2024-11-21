<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class UtilityReport2 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $route = '',
        private $queriedData = null,
    ) {
        //
    }

    public function render()
    {
        return view('components.reports2.utility-report2', [
            'route' => $this->route,
            'queriedData' => $this->queriedData,
            'class' => '',
        ]);
    }
}
