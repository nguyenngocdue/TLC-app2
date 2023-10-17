<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\UpdateUserSettings;
use App\Utils\Support\CurrentPathInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        
        $typeReport = Str::ucfirst(CurrentPathInfo::getTypeReport2($request));
        $entityReport = CurrentPathInfo::getEntityReport($request);
        $params = [
            '_entity' => $entityReport,
            'action' => 'updateReport' . $typeReport,
            'type_report' => $typeReport,
            'mode_option' => $this->mode
        ] + $input;
        $request->replace($params);
        (new UpdateUserSettings())($request);
        return redirect($request->getPathInfo());

    }
}
