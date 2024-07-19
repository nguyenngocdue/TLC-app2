<?php

namespace App\View\Components\HomeWebPage;

use Illuminate\View\Component;

class Video extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
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
        $link = "https://www.youtube.com/embed/ak69h2gPs9I";
        $dataSource = [
            "title" => "Click on the video for an overview",
            "iframe" => '<iframe width="1154" height="649" src="' . $link . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>'
        ];
        return view('components.home-web-page.video', $dataSource);
    }
}
