<?php

namespace App\View\Components\Renderer\Kanban;

use App\Models\Kanban_task;
use App\Models\Kanban_task_cluster;
use App\Models\Kanban_task_group;
use Illuminate\View\Component;

class Page extends Component
{
    private $debug = false;
    private $groupWidth = "w-72"; //10, 20, 32, 40, 52, 60, 72, 80, 96,

    function __construct(
        private $pageId = 1,
    ) {
    }

    private function getDataSourceClusters()
    {
        return Kanban_task_cluster::query()
            // ->with("getGroups.getTasks")
            ->where('kanban_page_id', $this->pageId)
            ->with(["getGroups" => function ($query) {
                $query->orderBy('order_no');

                $query->with(["getTasks" => function ($query) {
                    $query->orderBy('order_no');
                }]);
            }])
            ->orderBy('order_no')
            ->get();
    }

    function render()
    {
        $route_task = route(Kanban_task::getTableName() . ".kanban");
        $route_group = route(Kanban_task_group::getTableName() . ".kanban");
        $route_cluster = route(Kanban_task_cluster::getTableName() . ".kanban");

        return view("components.renderer.kanban.page", [
            'pageId' => $this->pageId,
            'clusters' => $this->getDataSourceClusters(),
            'hidden' => $this->debug ? "" : "hidden",
            'groupWidth' => $this->groupWidth,

            'routeTask' => $route_task,
            'routeGroup' => $route_group,
            'routeCluster' => $route_cluster,
        ]);
    }
}
