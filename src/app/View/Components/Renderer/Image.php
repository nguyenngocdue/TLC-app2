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
        private $title = null,
        private $text = null,
        private $class = "",
        private $spanClass = "w-10 h-10",
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
        $title = $this->title ? "title='$this->title'" : "";
        $img = "<img $title class='$this->class object-cover w-full h-full border mr-1' src='$this->src'/>";
        if ($this->href) $img = "<a class='' target='_blank' href='$this->href'>$img</a>";
        $img = "<span class='$this->spanClass block'>$img</span>";
        return $img;
    }
}
