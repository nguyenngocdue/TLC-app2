<?php

namespace App\View\Components\Renderer\Report;

use Illuminate\View\Component;

class HeaderReport extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource = []
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
        return view('components.renderer.report.header-report', [
            'dataSource' => $this->dataSource
        ]);
    }
}
