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
        if (!empty($request->input())) {
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        }
        [$viewportDate, $viewportMode] = $this->getUserSettingsViewAllMatrix();
        // $dataSource = $this->getDataSourceForViewCalendar($filterViewAllCalendar);
        return view('dashboards.pages.entity-view-all-matrix', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'title' => '(Matrix)',
            'viewportDate' => $viewportDate,
            'viewportMode' => $viewportMode,
            // 'valueAdvanceFilters' => $filterViewAllCalendar,
            'type' => Str::plural($this->type),
            'typeModel' => $this->typeModel,
            // 'dataSource' => $dataSource,
            'trashed' => false,
            'frameworkTook' => $this->frameworkTook,
        ]);
    }
}
