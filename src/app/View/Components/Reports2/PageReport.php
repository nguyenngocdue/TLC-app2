<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports2\Rp_pageController;
use App\Models\Rp_page;
use App\Models\Rp_report;
use Illuminate\Container\Container;
use Illuminate\View\Component;

class PageReport extends Component
{
    public function __construct(
        private $isLandscape = "",
    ) {
    }


    public function render()
    {
        return view('components.reports2.page-report', [
            // 'layoutStr' => $layoutStr,
        ]);
    }
}
