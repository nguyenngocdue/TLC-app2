<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;

class BlockReport extends Component
{
    public function __construct(
        private $blockDetails = [],
    ) {
    }

    public function render()
    {
        $blockDetails = $this->blockDetails;
        $blocks = [];
        foreach ($blockDetails as $item) {
            $array = [
                'col_span' => $item->col_span,
                'blocks' => $item->getBlock,
                'background_block' => $item->attachment_background->first()
            ];
            $blocks[] = $array;
        }
        return view('components.reports2.block-report', [
            'blocks' => $blocks
        ]);
    }
}
