<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class BlockReport extends Component
{
    public function __construct(
        private $blocks = [],
    ) {
    }

    public function render()
    {
        return view('components.reports2.block-report', [
            'blocks' => $this->blocks
        ]);
    }
}
