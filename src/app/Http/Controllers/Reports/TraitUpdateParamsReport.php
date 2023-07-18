<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\UpdateUserSettings;
use Illuminate\Support\Facades\Log;

trait TraitUpdateParamsReport
{
    protected function updateParams($request, $modeParams)
    {
        $input = $request->input();
        $isFormType = isset($input['form_type']);
        if ($isFormType && $input['form_type'] === 'updateParamsReport' || $isFormType && $input['form_type'] === 'updatePerPageReport' || $isFormType && $input['form_type'] === 'resetParamsReport') {
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        }
    }
}
