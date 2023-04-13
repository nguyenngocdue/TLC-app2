<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Image extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $src = '/images/helen.jpeg',
        private $href = null,
        private $w = null,
        private $h = null,
        private $title = null,
        private $text = null,
        private $class = "",
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
        $w = $this->w ? "width='$this->w'" : "";
        $h = $this->h ? "height='$this->h'" : "";
        $title = $this->title ? "title='$this->title'" : "";
        $img = "<img $title $w $h class='$this->class object-cover border mr-1' src='$this->src'/>";
        if ($this->href) $img = "<a class='' target='_blank' href='$this->href'>$img</a>";
        return $img;
    }
}
