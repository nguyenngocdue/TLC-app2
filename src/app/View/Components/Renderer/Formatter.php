<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Formatter extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $formatterName, private $dataLine)
    {
    }

    function statusColorRendered()
    {
        $title = $this->dataLine['title'] ?? "";
        $color = $this->dataLine['color'] ?? "";
        return "<x-renderer.tag color='$color'>$title</x-renderer.tag>";
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return $this->{$this->formatterName}();
        // return view('components.renderer.formatter');
    }
}
