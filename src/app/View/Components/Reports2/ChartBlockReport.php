<?php

namespace App\View\Components\Reports2;


use Illuminate\View\Component;

class ChartBlockReport extends Component
{
    public function __construct(
        private $width = "",


    ) {
    }

    private function createLayoutClass($isLandscape, $width, $height, $isFullWidth)
    {
        $class = $isFullWidth ? 'w-full' : ($isLandscape ? "w-[{$height}px] min-h-[{$width}px] " : "w-[{$width}px] min-h-[{$height}px]");
        return $class;
    }

    public function render()
    {
        return view('components.reports2.chart-block-report', []);
    }
}
