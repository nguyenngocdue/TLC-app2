<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class ItemRenderCheckSheet extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id,
        private $item,
    ) {
        // dump($item);

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $lines = $this->item->getRuns[0]->getLines;
        return view(
            'components.renderer.item-render-check-sheet',
            [
                'lines' => $lines,
            ]
        );
    }
}
