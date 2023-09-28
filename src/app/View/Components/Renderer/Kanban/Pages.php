<?php

namespace App\View\Components\Renderer\Kanban;

use App\Models\Kanban_task_page;
use Illuminate\View\Component;

class Pages extends Component
{
    function __construct(
        private $page = null,
    ) {
    }

    function render()
    {
        $route_page = route(Kanban_task_page::getTableName() . ".kanban");
        $pages = Kanban_task_page::query()
            ->orderBy('order_no')
            ->get();
        return view("components.renderer.kanban.pages", [
            'pages' => $pages,
            'routePage' => $route_page,
            'pageId' => $this->page->id,
        ]);
    }
}
