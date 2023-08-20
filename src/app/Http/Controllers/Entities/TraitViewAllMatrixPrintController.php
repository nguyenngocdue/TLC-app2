<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\UpdateUserSettings;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitViewAllMatrixPrintController
{
    private function indexViewAllMatrixPrint($request)
    {
        if (!empty($request->input())) {
            $request->merge([
                'action' => "updateViewAllMatrix",
                '_entity' => Str::plural($this->type),
            ]);
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        }
        return view('dashboards.pages.entity-view-all-matrix-print', [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'title' => '(Matrix)',
            'type' => Str::plural($this->type),
            'typeModel' => $this->typeModel,
            'trashed' => false,
            'frameworkTook' => $this->frameworkTook,
        ]);
    }
}
