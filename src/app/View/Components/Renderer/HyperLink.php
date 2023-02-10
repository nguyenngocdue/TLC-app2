<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class HyperLink extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $cell,
    ) {
        // dump($this->cell);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $link = $this->cell;
        if (str_starts_with($link, "No dataIndex for ")) return;

        return view('components.renderer.hyper-link', [
            'cell' => $this->cell,
        ]);
    }
}
