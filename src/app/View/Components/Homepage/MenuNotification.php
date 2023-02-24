<?php

namespace App\View\Components\Homepage;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class MenuNotification extends Component
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
        $notifications = auth()->user()->notifications->toArray();
        $unreadNotifications = auth()->user()->unreadNotifications->toArray();
        return view('components.homepage.menu-notification', [
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
        ]);
    }
}
