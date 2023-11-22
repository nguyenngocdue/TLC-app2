<?php

namespace App\View\Components\Homepage;

use App\Utils\Support\CurrentUser;
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
        $user = CurrentUser::get();
        $notifications = $user->notifications;
        $unreadNotifications = $notifications->whereNull('read_at');
        return view('components.homepage.menu-notification', [
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
        ]);
    }
}
