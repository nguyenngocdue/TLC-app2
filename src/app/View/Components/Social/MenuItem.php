<?php

namespace App\View\Components\Social;

use Illuminate\View\Component;

class MenuItem extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $href = "#" , private $title = "", private $src = "")
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.social.menu-item',[
            'href' => $this->href,
            'title' => $this->title,
            'src' => $this->src,
        ]);
    }
}
