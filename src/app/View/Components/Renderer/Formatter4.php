<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Formatter4 extends Component
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
        $title = $this->dataLine->title ?? "";
        $color = $this->dataLine->color ?? "";
        $colorIndex = $this->dataLine->color_index ?? "";
        $colorIndex = ($colorIndex) ? "colorIndex='$colorIndex'"  : "";
        return "<x-renderer.tag color='$color' $colorIndex>$title</x-renderer.tag>";
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
