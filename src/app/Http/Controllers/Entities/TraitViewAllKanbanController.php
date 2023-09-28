<?php

namespace App\Http\Controllers\Entities;

use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;

trait TraitViewAllKanbanController
{
    private function indexViewAllKanban($request)
    {
        if ($r = $this->updateUserSettings($request)) return $r;
        return view('dashboards.pages.entity-view-all-kanban', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            // 'title' => '(Matrix)',
            // 'type' => Str::plural($this->type),
            // 'typeModel' => $this->typeModel,
            // 'trashed' => false,
            'frameworkTook' => $this->frameworkTook,
        ]);
    }
}
