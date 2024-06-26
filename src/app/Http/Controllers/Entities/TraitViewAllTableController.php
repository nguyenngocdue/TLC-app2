<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitViewAllTableController
{
    use TraitViewAllFunctions;

    private function indexViewAllTable($request, $trashed)
    {
        $basicFilter = $request->input('basic_filter');
        if ($basicFilter || !empty($basicFilter)) {
            (new UpdateUserSettings())($request);
        }
        if (!$request->input('page') && !empty($request->input())) {
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        }
        [$perPage, $columnLimit, $advanceFilters, $currentFilter, $refreshPage] = $this->getUserSettingsViewAll();
        // Log::info($columnLimit);
        $type = Str::plural($this->type);
        $columns = $this->getColumns($type, $columnLimit, $trashed);
        $dataSource = $this->getDataSource($advanceFilters, $trashed)->paginate($perPage);
        $this->attachEloquentNameIntoColumn($columns); //<< This must be before attachRendererIntoColumn
        $this->attachRendererIntoColumn($columns);
        // $searchableArray = App::make($this->typeModel)->toSearchableArray();
        $app = LibApps::getFor($this->type);
        $tableTrueWidth = !($app['hidden'] ?? false);

        $cu = CurrentUser::get();
        $showAdvanceFilterForm = !$cu->isProjectClient();

        //Beta to show true width
        // if (app()->isProduction() || app()->isLocal()) $tableTrueWidth = false;
        return view('dashboards.pages.entity-view-all', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'title' => $trashed ? '(Trash)' : '',
            'perPage' => $perPage,
            'showAdvanceFilterForm' => $showAdvanceFilterForm,
            'valueAdvanceFilters' => $advanceFilters,
            'refreshPage' => $refreshPage,
            'type' => $type,
            'typeModel' => $this->typeModel,
            'columns' => $columns,
            'dataSource' => $dataSource,
            'currentFilter' => $currentFilter,
            'tableTrueWidth' => $tableTrueWidth,
            'frameworkTook' => $this->frameworkTook,
            'trashed' => $trashed,
            'tabPane' => $this->getTabPane($advanceFilters),
        ]);
    }
}
