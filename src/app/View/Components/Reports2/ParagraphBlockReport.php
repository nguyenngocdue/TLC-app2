<?php

namespace App\View\Components\Reports2;


use Illuminate\View\Component;

class ParagraphBlockReport extends Component
{
    public function __construct(
        private $width = "",


    ) {
    }

    public function render()
    {
        return view('components.reports2.paragraph-block-report', []);
    }
}
