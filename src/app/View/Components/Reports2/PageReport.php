<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports2\Rp_page_block_detailController;
use App\Http\Controllers\Reports2\Rp_pageController;
use Illuminate\View\Component;

class PageReport extends Component
{
    public function __construct(
        private $page
    ) {
    }

    private function createLayoutClass($isLandscape, $width, $height, $isFullWidth)
    {
        $FullWidthClass = $width  ? "w-[{$width}px]" : 'w-full';
        $width = $width ? $width : 1000;
        $height = $height ? $height : 1410;
        $class = $isFullWidth ? $FullWidthClass : ($isLandscape ? "width :{$height}px;  height: {$width}px;" : "width :{$width}px; height:{$height}px;");
        return $class;
    }

    private function isEmptyCollection($coll)
    {
        return $coll->isEmpty();
    }

    public function render()
    {
        $page = $this->page;
        $pageArray = $page?->toArray();
        $attachmentBackgroundPage = $page->attachment_background->first()?->toArray();
        $blocks = $page->getBlockDetails->sortBy('order_no')?->toArray();
        $layoutClass = $this->createLayoutClass(
            $pageArray['is_landscape'],
            $pageArray['width'],
            $pageArray['height'],
            $pageArray['is_full_width']
        );


        if ($attachmentBackgroundPage) {
            $backgroundPagePath = env('AWS_ENDPOINT') . '/tlc-app//' . $attachmentBackgroundPage['url_media'];
        }

        return view('components.reports2.page-report', [
            'layoutClass' => $layoutClass,
            'letterHeadId' => $pageArray['letter_head_id'],
            'letterFooterId' => $pageArray['letter_footer_id'],
            'content' => $pageArray,
            'blocks' => $blocks,
            'backgroundPage' => $backgroundPagePath ?? '',
        ]);
    }
}
