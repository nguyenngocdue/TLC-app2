<?php

namespace App\View\Components\Renderer\Notification;

use Illuminate\View\Component;

class AllNotifications extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $dataSource, private $showAll = false)
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
        $notifications = $this->dataSource;
        return view('components.renderer.notification.all-notifications', [
            'notifications' => $notifications->groupBy('group_name'),
            'isShowAll' => !$this->showAll,
        ]);
    }
}
