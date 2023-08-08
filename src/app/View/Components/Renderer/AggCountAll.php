<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class AggCountAll extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $rendererUnit = '',
        private $renderRaw = false,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return "use agg_count for renderer instead of agg_count_all";
    }
}
