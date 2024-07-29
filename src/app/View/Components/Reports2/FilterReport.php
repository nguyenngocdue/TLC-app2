<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class FilterReport extends Component
{
    public function __construct(
        private $reportId = "",
        private $filterModes = [],

    ) {
    }



    public function render()
    {
        $filterModes = collect($this->filterModes);
        $names = $filterModes->mapWithKeys(function ($filterMode) {
            return [$filterMode->name => $filterMode->name];
        });

        // dd($names);


        return view('components.reports2.filter-report', [
            'dataSource' => $names,
        ]);
    }
}
