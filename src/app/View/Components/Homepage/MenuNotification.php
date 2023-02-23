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
        $assigneeNotifications = [];
        $monitorNotifications = [];
        $createdNotifications = [];
        foreach ($notifications as $key => $value) {
            switch ($value['data']['type']) {
                case 'assignee':
                    $assigneeNotifications[] = $value;
                    break;
                case 'monitor':
                    $monitorNotifications[] = $value;
                    break;
                case 'created':
                    $createdNotifications[] = $value;
                    break;
                default:
                    break;
            }
        }
        return view('components.homepage.menu-notification', [
            'assigneeNotifications' => $assigneeNotifications,
            'monitorNotifications' => $monitorNotifications,
            'createdNotifications' => $createdNotifications,
        ]);
    }
}
