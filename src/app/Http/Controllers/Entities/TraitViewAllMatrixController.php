<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\UpdateUserSettings;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitViewAllMatrixController
{
    private function indexViewAllMatrix($request)
    {
        if (!empty($request->input())) {
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        }
        return view('dashboards.pages.entity-view-all-matrix', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'title' => '(Matrix)',
            'type' => Str::plural($this->type),
            'typeModel' => $this->typeModel,
            'trashed' => false,
            'frameworkTook' => $this->frameworkTook,
        ]);
    }
}
