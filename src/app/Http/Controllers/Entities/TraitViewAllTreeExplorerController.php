<?php

namespace App\Http\Controllers\Entities;

use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;

trait TraitViewAllTreeExplorerController
{
    private function indexViewAllTreeExplorer($request)
    {
        if ($r = $this->updateUserSettings($request)) return $r;
        return view('dashboards.pages.entity-view-all-tree-explorer', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'title' => '(Signature)',
            'type' => Str::plural($this->type),
            'typeModel' => $this->typeModel,
            'trashed' => false,
            'frameworkTook' => $this->frameworkTook,
        ]);
    }
}
