<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Card extends Component
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
        return view('components.renderer.card'); //->with(compact('title', 'description'));
        // return function (array $data) {
        //     dump($data['componentName']);
        //     dump($data['attributes']);
        //     dump($data['slot']);

        //     $desc = join("", $data['attributes']["items"]);
        //     $title = "AA";
        //     $description = "HHH";
        //     dump($desc);

        //     return 'components.renderer.card';
        // };
    }
}
