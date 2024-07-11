<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports2\Rp_page_block_detailController;
use App\Http\Controllers\Reports2\Rp_pageController;
use Illuminate\View\Component;

class ReportBuilder extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        // Test Page
        $rpPageContr = new Rp_pageController();


        // UT_Page - 1A & 1B, 2A & 2B, 3A, 4A & 4B
        $pages = $rpPageContr->getPageLandscape();
        $pages = $rpPageContr->getPagePortrait();
        $pages = $rpPageContr->getPageWidthAndHeight();
        $pages = $rpPageContr->getPageFullWidth();
        $pages = $rpPageContr->getPageHeadFooterBackground();
        $pages = $rpPageContr->getPageLetterHeadStackable();


        // Test Page Block
        $rpPageBlockDetailCtrl = new Rp_page_block_detailController();
        $pages = $rpPageBlockDetailCtrl->getPageBlocksColSpan();
        // $pages = $rpPageBlockDetailCtrl->getPageBlocksBackground();
        // $pages = $rpPageBlockDetailCtrl->getPageBlocksOrderNo();
        // dd($pages);

        // UT_Page - 3A
        // $pages = $rpPageContr->getPageOrderNo();


        return view('components.reports2.report-builder', [
            'pages' => $pages,
        ]);
    }
}
