<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class ReportPage extends Component
{
    public function __construct(
        private $page,
        private $report,
        private $pageScaleFactor = 0.75,
        private $w = 1000,
        private $h = 1410,
    ) {}

    private function createLayoutStyle($isLandscape, $width, $height, $isFullWidth, $pageBackgroundPath)
    {
        $fullWidthClass = $width  ? "width :{$width}px" : "width :{$this->w}px";
        $width = $width ? $width  : $this->w * $this->pageScaleFactor;
        $height = $height ? $height  : $this->h * $this->pageScaleFactor;
        $style = $isFullWidth ? $fullWidthClass : ($isLandscape ? "width :{$height}px;  height: {$width}px;" : "width :{$width}px; height:{$height}px;");
        $style = $pageBackgroundPath ? $style . " background-image: url('{$pageBackgroundPath}');" : $style;
        return $style;
    }

    public function render()
    {
        $page = $this->page;
        $pageItem = $page?->toArray();
        $pageBackgroundAttachment = $page->attachment_background->first()?->toArray();
        $blockDetails = $page->getBlockDetails->sortBy('order_no');

        if ($pageBackgroundAttachment) {
            $pageBackgroundPath = app()->pathMinio() . $pageBackgroundAttachment['url_media'];
        }

        $layoutStyle = $this->createLayoutStyle(
            $pageItem['is_landscape'],
            $pageItem['width'],
            $pageItem['height'],
            $pageItem['is_full_width'],
            $pageBackgroundPath ?? ''
        );

        return view('components.reports2.report-page', [
            'report' => $this->report,
            'layoutStyle' => $layoutStyle,
            'letterHeadId' => $pageItem['letter_head_id'],
            'letterFooterId' => $pageItem['letter_footer_id'],
            'content' => $pageItem,
            'blockDetails' => $blockDetails,
            'pageBackgroundPath' => $pageBackgroundPath ?? '',
        ]);
    }
}
