<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports2\Rp_page_block_detailController;
use App\Http\Controllers\Reports2\Rp_pageController;
use Illuminate\View\Component;

class PageReport extends Component
{
    public function __construct(
        private $page,
        private $reportId,
        private $factorPage = 0.75,
        private $w = 1000,
        private $h = 1410,
    ) {
    }

    private function createLayoutClass($isLandscape, $width, $height, $isFullWidth, $backgroundPagePath)
    {
        $FullWidthClass = $width  ? "w-[{$width}px]" : 'w-full';
        $width = $width ? $width  : $this->w * $this->factorPage;
        $height = $height ? $height  : $this->h * $this->factorPage;
        $class = $isFullWidth ? $FullWidthClass : ($isLandscape ? "width :{$height}px;  height: {$width}px;" : "width :{$width}px; height:{$height}px;");
        $class = $backgroundPagePath ? $class . " background-image: url('{$backgroundPagePath}');" : $class;
        return $class;
    }

    public function render()
    {
        $page = $this->page;
        $pageArray = $page?->toArray();
        $attachmentBackgroundPage = $page->attachment_background->first()?->toArray();
        $blockDetails = $page->getBlockDetails->sortBy('order_no');

        if ($attachmentBackgroundPage) {
            $backgroundPagePath = env('AWS_ENDPOINT') . '/tlc-app//' . $attachmentBackgroundPage['url_media'];
        }

        $layoutClass = $this->createLayoutClass(
            $pageArray['is_landscape'],
            $pageArray['width'],
            $pageArray['height'],
            $pageArray['is_full_width'],
            $backgroundPagePath ?? ''
        );



        return view('components.reports2.page-report', [
            'reportId' => $this->reportId,
            'layoutClass' => $layoutClass,
            'letterHeadId' => $pageArray['letter_head_id'],
            'letterFooterId' => $pageArray['letter_footer_id'],
            'content' => $pageArray,
            'blockDetails' => $blockDetails,
            'backgroundPagePath' => $backgroundPagePath ?? '',
        ]);
    }
}
