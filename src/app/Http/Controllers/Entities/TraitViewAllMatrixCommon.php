<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\UpdateUserSettings;
use Illuminate\Support\Str;

trait TraitViewAllMatrixCommon
{
    private function updateUserSettings($request)
    {
        if (!empty($request->input())) {
            $request->merge([
                'action' => "updateViewAllMatrix",
                '_entity' => Str::plural($this->type),
            ]);
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        } else {
            return null;
        }
    }
}
