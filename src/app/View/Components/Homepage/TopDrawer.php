<?php

namespace App\View\Components\Homepage;

use App\Http\Controllers\Workflow\LibApps;
use Illuminate\View\Component;

class TopDrawer extends Component
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
        $allApps = LibApps::getAllNavbarBookmark();
        return view('components.homepage.top-drawer', [
            'allApps' => array_values($allApps),
            'route' => route('updateBookmark'),
        ]);
    }
}
