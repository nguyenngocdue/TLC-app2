<?php

namespace App\View\Components\HomeWebPage;

use Illuminate\View\Component;

class Carousel extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource = null,
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
        $dataSource = $this->dataSource;
        return view('components.home-web-page.carousel', [
            "images" => $dataSource['images'],
            "contents" => $dataSource['contents'],
        ]);
    }
}
