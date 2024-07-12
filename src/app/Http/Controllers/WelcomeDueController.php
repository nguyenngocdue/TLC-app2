<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Reports2\Rp_page_block_detailController;
use App\Http\Controllers\Reports2\Rp_pageController;
use Illuminate\Http\Request;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }



    public function index(Request $request)
    {


        // Test Page
        $rpPageCtrl = new Rp_pageController();
        $isLandscape = $rpPageCtrl->getPageLandscape() ? true : false;
        $isPortrait = $rpPageCtrl->getPagePortrait() ? true : false;
        $pagesWidthAndHeight = $rpPageCtrl->getPageWidthAndHeight();
        $pagesFullWidth = $rpPageCtrl->getPageFullWidth();
        $pagesOrderNo = $rpPageCtrl->getPageOrderNo();
        $pagesLetterHeadStackable = $rpPageCtrl->getPageLetterHeadStackable();
        $pagesHeaderFooterBackground = $rpPageCtrl->getPageHeadFooterBackground();

        // Test Page Block
        $rpPageBlockDetailCtrl = new Rp_page_block_detailController();
        $pageBlocksColSpan = $rpPageBlockDetailCtrl->getPageBlocksColSpan();
        $pageBlocksBackground = $rpPageBlockDetailCtrl->getPageBlocksBackground();
        $pageBlocksOrderNo = $rpPageBlockDetailCtrl->getPageBlocksOrderNo();


        // dump($pagesOrderNo);



        // $layoutStr = $this->getLayoutStr($isLandscape, $this->width, $this->height);


        return view("welcome-due-ut-reports", [
            // 'layoutStr' = $this->layoutStr
        ]);
    }
}
