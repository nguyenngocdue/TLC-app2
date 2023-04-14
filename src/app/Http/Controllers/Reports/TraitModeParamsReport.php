<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentUser;

trait TraitModeParamsReport
{
    protected function getModeParams($request, $str = '')
    {
        $currentMode = $this->mode;
        $typeReport = CurrentPathInfo::getTypeReport($request, $str);
        $entity = CurrentPathInfo::getEntityReport($request, $str);
        $settings = CurrentUser::getSettings();
        if (isset($settings[$entity][$typeReport][$currentMode])) {
            $modeParams = $settings[$entity][$typeReport][$currentMode];
            return $modeParams;
        }
        return [];
    }
}
