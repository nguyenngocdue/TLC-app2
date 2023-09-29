<?php

namespace App\Http\Controllers\Entities;

use App\Models\Kanban_task_page;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;

trait TraitViewAllKanbanController
{
    private function indexViewAllKanban($request)
    {
        $pageId = 1;
        $page = Kanban_task_page::find($pageId);
        // dd($page);

        if ($r = $this->updateUserSettings($request)) return $r;
        return view('dashboards.pages.entity-view-all-kanban', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'page' => $page,
            // 'title' => '(Matrix)',
            // 'type' => Str::plural($this->type),
            // 'typeModel' => $this->typeModel,
            // 'trashed' => false,
            'frameworkTook' => $this->frameworkTook,
        ]);
    }
}
