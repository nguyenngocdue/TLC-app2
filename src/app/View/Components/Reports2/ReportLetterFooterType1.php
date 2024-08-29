<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports2\Rp_page_block_detailController;
use App\Http\Controllers\Reports2\Rp_pageController;
use Illuminate\View\Component;

class ReportLetterFooterType1 extends Component
{
    public function __construct(
        private $width = "",
        private $height = "",
        private $isLandscape = false,
        private $isFullWidth = false,
        private $content = ''
    ) {
    }

    public function render()
    {
        return view('components.reports2.report-letter-footer-type1', []);
    }
}
