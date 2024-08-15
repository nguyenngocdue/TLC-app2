<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_report;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Dropdown9 extends Component
{
    //for report 2
    public function __construct(
        private $filterLinkDetails = [],
        private $name = 'No name',
        private $allowClear = false,

    ) {}


    public function render()
    {
        $filterLinkDetails = (object)$this->filterLinkDetails;

        $rpLinks = $filterLinkDetails->map(function ($item) {
            $rpLinkId = $item->getFilterLink->linked_to_report_id;
            $rpLink = Rp_report::find($rpLinkId);
            return $rpLink;
        });

        return view('components.reports2.dropdown9', [
            'rpLinks' =>  $rpLinks,
            'name' => $this->name,
            'allowClear' => $this->allowClear,
        ]);
    }
}
