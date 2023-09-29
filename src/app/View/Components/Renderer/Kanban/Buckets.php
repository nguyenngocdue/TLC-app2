<?php

namespace App\View\Components\Renderer\Kanban;

use App\Models\Kanban_task_bucket;
use App\Models\Kanban_task_page;
use Illuminate\View\Component;

class Buckets extends Component
{
    function __construct(
        private $page = null,
    ) {
    }

    private function getDataSourceBuckets()
    {
        return Kanban_task_bucket::query()
            // ->with("getGroups.getTasks")
            // ->where('kanban_page_id', $this->page->id)
            ->with(["getPages" => function ($query) {
                $query->orderBy('order_no');

                // $query->with(["getTasks" => function ($query) {
                //     $query->orderBy('order_no');
                // }]);
            }])
            ->orderBy('order_no')
            ->get();
    }

    function render()
    {
        $route_page = route(Kanban_task_page::getTableName() . ".kanban");
        $route_bucket = route(Kanban_task_bucket::getTableName() . ".kanban");

        $buckets = $this->getDataSourceBuckets();
        return view("components.renderer.kanban.buckets", [
            'buckets' => $buckets,
            'routePage' => $route_page,
            'routeBucket' => $route_bucket,
            'pageId' => $this->page->id,
        ]);
    }
}
