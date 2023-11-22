<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class AllNotifications2 extends Component
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
        dd($notifications);
        $assigneeNotifications = [];
        $monitorNotifications = [];
        $createdNotifications = [];
        $totalAssigneeNotifications = sizeof($assigneeNotifications);
        $totalCreatedNotifications = sizeof($createdNotifications);
        $totalMonitorNotifications = sizeof($monitorNotifications);
        if (!$this->showAll) {
            $assigneeNotifications = array_slice($assigneeNotifications, 0, 5, true);
            $createdNotifications = array_slice($createdNotifications, 0, 5, true);
            $monitorNotifications = array_slice($monitorNotifications, 0, 5, true);
        }
        return view('components.renderer.all-notifications', [
            'totalAssigneeNotifications' => $totalAssigneeNotifications,
            'assigneeNotifications' => $assigneeNotifications,
            'totalCreatedNotifications' => $totalCreatedNotifications,
            'createdNotifications' => $createdNotifications,
            'totalMonitorNotifications' => $totalMonitorNotifications,
            'monitorNotifications' => $monitorNotifications,
            'isShowAll' => !$this->showAll,
        ]);
    }
}
