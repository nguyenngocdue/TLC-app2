<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_page;
use App\Models\Rp_report;
use Illuminate\View\Component;

class PageReport extends Component
{
    public function __construct(
        private $isLandscape = true,
        private $width = '1122px',
        private $height = '794px',
    ) {
    }


    private function getLayoutStr($isLandscape, $width, $height)
    {
        return $isLandscape ? "max-w-[{$width}] min-h-[{$height}]" : "max-w-[{$height}] min-h-[{$width}]";
    }

    public function render()
    {
        $model = new Rp_page();
        dd($model->get()->toArray());

        $layoutStr = $this->getLayoutStr($this->isLandscape, $this->width, $this->height);
        return view('components.reports2.page-report', [
            'layoutStr' => $layoutStr,
        ]);
    }
}
