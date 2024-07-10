<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class PageReport extends Component
{
    public function __construct(
        private $isLandscape = false,
        private $width = '1122px',
        private $height = '794px',
    ) {
    }

    private function getLayoutStr($isLandscape, $width, $height)
    {
        return $isLandscape ? "max-w-[{$width}] max-h-[{$height}]" : "max-w-[{$height}] h-[{$width}]";
    }

    public function render()
    {
        $layoutStr = $this->getLayoutStr($this->isLandscape, $this->width, $this->height);
        return view('components.reports2.page-report', [
            'layoutStr' => $layoutStr,
        ]);
    }
}
