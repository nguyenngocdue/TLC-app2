<?php

namespace App\View\Components\Renderer\Kanban;

use App\Models\Kanban_task_cluster;
use Illuminate\View\Component;

class Page extends Component
{
    private $debug = false;
    private $groupWidth = "w-72"; //10, 20, 32, 40, 52, 60, 72, 80, 96,

    function __construct(
        private $page = null,
    ) {
    }

    private function getDataSourceClusters()
    {
        return Kanban_task_cluster::query()
            // ->with("getGroups.getTasks")
            ->where('kanban_page_id', $this->page ? $this->page->id : null)
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
        if (is_null($this->page)) return "";
        return view("components.renderer.kanban.page", [
            'page' => $this->page,
            'pageId' => $this->page ? $this->page->id : null,
            'clusters' => $this->getDataSourceClusters(),
            'hidden' => $this->debug ? "" : "hidden",
            'groupWidth' => $this->groupWidth,
        ]);
    }
}
