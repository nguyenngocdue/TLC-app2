<?php

namespace App\View\Components\Homepage;

use Illuminate\View\Component;

class Sidebar2 extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $entityItems = SidebarEntityItems::getAll();
        $adminItems = SidebarAdminItems::getAll();
        $items = [
            ...$entityItems,
            "-",
            ...$adminItems,
        ];
        return view('components.homepage.sidebar2')->with(compact('items'));
    }
}
