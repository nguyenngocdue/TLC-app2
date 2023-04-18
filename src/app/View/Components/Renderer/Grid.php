<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Grid extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $colSpan = 3,
        private $gap = 2,
        private $items = [],
        private $itemRenderer = "p",
        private $groupBy = false,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (empty($this->items)) return "<x-renderer.emptiness class='border' />";
        if ($this->groupBy) usort($this->items, fn ($a, $b) => strcasecmp($a[$this->groupBy], $b[$this->groupBy]));
        // dump($this->groupBy);

        return view('components.renderer.grid', [
            'colSpan' => $this->colSpan,
            'gap' => $this->gap,
            'items' => $this->items,
            'itemRenderer' => $this->itemRenderer,
            'groupBy' => $this->groupBy,
        ]);
    }
}
