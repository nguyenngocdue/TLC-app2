<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Http\Controllers\UpdateUserSettings;
use App\Utils\Support\CurrentRoute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitViewAllMatrixController
{
    use TraitViewAllFunctions;

    private function indexViewAllMatrix($request)
    {

        [, $filterViewAllCalendar] = $this->getUserSettingsViewAllCalendar();
        $dataSource = $this->getDataSourceForViewCalendar($filterViewAllCalendar);
        return view('dashboards.pages.entity-view-all-matrix', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'title' => '(Matrix)',
            'valueAdvanceFilters' => $filterViewAllCalendar,
            'type' => Str::plural($this->type),
            'typeModel' => $this->typeModel,
            'dataSource' => $dataSource,
            'trashed' => false,
            'frameworkTook' => $this->frameworkTook,
        ]);
    }
}
