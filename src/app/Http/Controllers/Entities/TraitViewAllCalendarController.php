<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\User;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitViewAllCalendarController
{
    use TraitViewAllFunctions;

    private function indexViewAllCalendar($request)
    {
        [, $filterViewAllCalendar] = $this->getUserSettingsViewAllCalendar();
        $dataSource = $this->getDataSourceForViewCalendar($filterViewAllCalendar);
        return view('dashboards.pages.entity-view-all-calendar', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'title' => '(Calendar)',
            'valueAdvanceFilters' => $filterViewAllCalendar,
            'type' => Str::plural($this->type),
            'typeModel' => $this->typeModel,
            'dataSource' => $dataSource,
            'trashed' => false,
            'frameworkTook' => $this->frameworkTook,
        ]);
    }
}
