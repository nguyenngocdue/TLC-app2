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
        private $class = '',
        private $blockTitle = '',
        private $configuredCols = [],
    ) {
        //
    }

    public function render()
    {
        return view('components.reports2.utility-report2', [
            'route' => $this->route,
            'queriedData' => $this->queriedData,
            'class' => $this->class,
            'blockTitle' => $this->blockTitle,
            'configuredCols' => $this->configuredCols,
        ]);
    }
}
