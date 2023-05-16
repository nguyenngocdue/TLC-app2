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
        private $title = "Untitled",
        private $figure = "???",
        private $table = null,
        private $projectId = null,
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
        if (app()->isLocal()) {
            dump($this->table);
            dump($this->projectId);
        }
        return view(
            'components.renderer.report.widget',
            [
                'title' => $this->title,
                'figure' => $this->figure,
            ]
        );
    }
}
