<?php

namespace App\View\Components\Social;

use App\Models\Post;
use Illuminate\View\Component;

class Feed extends Component
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
        dd(Post::all());
        return view('components.social.feed');
    }
}
