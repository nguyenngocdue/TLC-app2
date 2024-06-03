<?php

namespace App\View\Components\Renderer\Kanban;

use App\Models\Kanban_task_bucket;
use App\Models\Kanban_task_page;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class Buckets extends Component
{
    function __construct(
        private $page = null,
        private $groupWidth = null,
    ) {
    }

    private function getDataSourceBuckets()
    {
        $uid = CurrentUser::id();
        $pagesOwnedByMe = Kanban_task_page::query()
            ->where('owner_id', $uid)
            ->get()
            ->pluck('id')
            ->toArray();

        $pagesAssignedToMe = [];
        $allPages = Kanban_task_page::query()->get();
        foreach ($allPages as $page) {
            $members = $page->getMonitors1()->pluck('users.id')->toArray();
            if (in_array($uid, $members)) $pagesAssignedToMe[] = $page->id;
        }

        $showPages = [...$pagesOwnedByMe, ...$pagesAssignedToMe];

        $buckets = Kanban_task_bucket::query()
            // ->with("getGroups.getTasks")
            // ->where('kanban_page_id', $this->page->id)
            ->with(["getPages" => function ($query) use ($showPages) {
                $query
                    ->whereIn('id', $showPages)
                    ->orderBy('order_no');

                // $query->with(["getTasks" => function ($query) {
                //     $query->orderBy('order_no');
                // }]);
            }])
            ->orderBy('order_no')
            ->get();

        $result = [];
        foreach ($buckets as $bucket) {
            if ($bucket->getPages->isEmpty() && $bucket->owner_id != $uid) continue;
            $result[] = $bucket;
        }

        return $result;
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
            'pageId' => $this->page ? $this->page->id : null,
            'groupWidth' => $this->groupWidth,
        ]);
    }
}
