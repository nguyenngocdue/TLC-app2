<?php

namespace App\Http\Controllers\Entities;

use App\Models\Kanban_task_page;
use App\Utils\Constant;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;

trait TraitViewAllKanbanController
{
    private function indexViewAllKanban($request)
    {
        $cu = CurrentUser::get();
        $pageId = $cu->settings['kanban_task_page'][Constant::VIEW_ALL]['current_page'] ?? null;
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
