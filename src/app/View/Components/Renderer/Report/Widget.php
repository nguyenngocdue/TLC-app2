<?php

namespace App\View\Components\Renderer\Report;

use Illuminate\View\Component;

class Widget extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $meta = [],
        private $metric = [],
        private $key = "",
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
        return view(
            'components.renderer.report.widget',
            [
                'key' => $this->key,
                'meta' => $this->meta,
                'metric' => $this->metric,
            ]
        );
    }
}
