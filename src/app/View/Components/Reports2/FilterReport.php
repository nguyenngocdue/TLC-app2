<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class FilterReport extends Component
{
    public function __construct(
        private $reportId = "",
        private $filterModes = [],
        private $filterDetails = [],

    ) {
    }


    public function render()
    {
        $filterModes = collect($this->filterModes);
        $filterDetails = $this->filterDetails;

        dump($filterDetails);


        $keysNames = $filterModes->mapWithKeys(function ($filterMode) {
            return [$filterMode->name => $filterMode->name];
        });
        $params = [
            "forward_to_mode" => "Mode 2"
        ];

        // dd($names);
        return view('components.reports2.filter-report', [
            'keysNames' => $keysNames,
            'params' => $params,
            'filterDetails' => $filterDetails
        ]);
    }
}
