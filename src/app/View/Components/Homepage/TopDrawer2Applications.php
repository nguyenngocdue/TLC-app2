<?php

namespace App\View\Components\Homepage;

use Illuminate\View\Component;

class TopDrawer2Application extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.homepage.top-drawer2-applications', [
            'route' => route('updateBookmark'),
        ]);
    }
}
