<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports2\Rp_page_block_detailController;
use App\Http\Controllers\Reports2\Rp_pageController;
use Illuminate\View\Component;

class PageReport extends Component
{
    public function __construct(
        private $width = "",
        private $height = "",
        private $isLandscape = false,
        private $isFullWidth = false,
        private $content = '',
        private $letterHeadId = 0,
        private $letterFooterId = 0,
        private $blocks = [],
    ) {
    }

    private function createLayoutClass($isLandscape, $width, $height, $isFullWidth)
    {
        $FullWidthClass = $width  ? "w-[{$width}px]" : 'w-full';
        $width = $width ? $width : 1000;
        $height = $height ? $height : 1410;
        $class = $isFullWidth ? $FullWidthClass : ($isLandscape ? "w-[{$height}px] h-[{$width}px] " : "w-[{$width}px] h-[{$height}px]");
        return $class;
    }

    public function render()
    {


        $layoutClass = $this->createLayoutClass($this->isLandscape, $this->width, $this->height, $this->isFullWidth);

        return view('components.reports2.page-report', [
            'layoutClass' => $layoutClass,
            'letterHeadId' => $this->letterHeadId,
            'letterFooterId' => $this->letterFooterId,
            'content' => $this->content,
            'blocks' => $this->blocks,
        ]);
    }
}
