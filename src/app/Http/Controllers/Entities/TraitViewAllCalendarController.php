<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Http\Controllers\UpdateUserSettings;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitViewAllCalendarController
{
    use TraitViewAllFunctions;

    private function indexViewAllCalendar($request)
    {
        if ($request->input('view_type')) {
            (new UpdateUserSettings())($request);
            // return redirect($request->getPathInfo());
        }
        [,,,,,,,, $filterViewAllCalendar] = $this->getUserSettings();
        $dataSource = $this->getDataSource($filterViewAllCalendar);
        return view('dashboards.pages.entity-view-all', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'title' => 'View All Calendar',
            'valueAdvanceFilters' => $filterViewAllCalendar,
            'type' => Str::plural($this->type),
            'typeModel' => $this->typeModel,
            'dataSource' => $dataSource,
            'trashed' => false,
            'tabs' => $this->getTabs(),
            // 'searchTitle' => "Search by " . join(", ", array_keys($searchableArray)),
            'frameworkTook' => $this->frameworkTook,
        ]);
    }
}
