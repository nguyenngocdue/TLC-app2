<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_report;
use Illuminate\View\Component;

class Dropdown9 extends Component
{
    //for report 2
    public function __construct(
        private $filterLinkDetails = [],
        private $name = 'No name',
        private $currentParams = [],
        private $title = "No title",
        private $allowClear = false,
        private $routeName = '',


        private $entityType = '',
        private $entityType2 = '',
        private $reportId = '',

    ) {}


    public function render()
    {
        $currentParams = $this->currentParams;
        $filterLinkDetails = (object)$this->filterLinkDetails;

        $rpLinks = $filterLinkDetails->map(function ($item) {
            return $item->getFilterLink;
        });

        $paramsCurrentRp = [];

        return view('components.reports2.dropdown9', [
            'rpLinks' =>  $rpLinks,
            'name' => $this->name,
            'currentParams' => $currentParams,
            'paramsCurrentRp' => $paramsCurrentRp,
            'name' => $this->name,
            'title' => $this->title,
            'allowClear' => $this->allowClear,
            'routeName' => $this->routeName,
            'entityType' => $this->entityType,
            'entityType2' => $this->entityType2,
            'reportId' => $this->reportId

        ]);
    }
}
