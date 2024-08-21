<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_report;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Dropdown9 extends Component
{
    //for report 2
    public function __construct(
        private $rpFilterLinks = [],
        private $name = 'No name',
        private $allowClear = false,

    ) {}


    public function render()
    {
        $rpFilterLinks = (object)$this->rpFilterLinks;

        $rpLinks = $rpFilterLinks->map(function ($item) {
            $rpLinkId = $item->report_filter_link_id;
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
