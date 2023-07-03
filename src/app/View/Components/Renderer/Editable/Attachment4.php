<?php

namespace App\View\Components\Renderer\Editable;

use Illuminate\View\Component;

class Attachment4 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return function (array $data) {
            return "<x-renderer.thumbnails>" . $data['slot'] . "</x-renderer.thumbnails>";
        };
    }
}
