<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\UpdateUserSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait TraitForwardModeReport
{
    protected function forwardToMode($request, $params)
    {
        $input = $request->input();
        $isFormType = isset($input['form_type']);
        if ($isFormType && $input['form_type'] === 'updateParamsReport' || $isFormType && $input['form_type'] === 'updatePerPageReport' || $isFormType && $input['form_type'] === 'resetParamsReport') {
            (new UpdateUserSettings())($request);
            return redirect($request->getPathInfo());
        }
        if (isset($input['mode_option'])) {
            Log::info("010");
            $mode = $input['mode_option'];
            $routeName = explode('/', $request->getPathInfo())[2];
            if (isset($input['form_type']) && $input['form_type'] === 'updateParams') {
                (new UpdateUserSettings())($request);
                return redirect($request->getPathInfo());
            }
            return redirect(route($routeName . '_' . $mode));
        }
    }
}
