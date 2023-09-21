<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;

trait TraitParamsSettingReport
{
    private static function removeNullItems($params)
    {
        $params = array_filter($params, function ($value) {
            if (is_array($value)) {
                return isset($value[0]) &&  !is_null($value[0]);
            }
            return true;
        });
        return $params;
    }
    protected function getParams($request, $str = '')
    {
        $currentMode = $this->mode;
        $typeReport = CurrentPathInfo::getTypeReport($request, $str);
        $entity = CurrentPathInfo::getEntityReport($request, $str);
        $settings = CurrentUser::getSettings();
        if (isset($settings[$entity][$typeReport][$currentMode])) {
            $params = $settings[$entity][$typeReport][$currentMode];
            if (Report::isNullParams($params)) {
                return self::removeNullItems($params);
            }
            return $params;
        }
        return [];
    }
}
