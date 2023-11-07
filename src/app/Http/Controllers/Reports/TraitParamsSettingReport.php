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
        $typeReport = CurrentPathInfo::getTypeReport2($request, $str);
        $entity = CurrentPathInfo::getEntityReport($request, $str);
        $settings = CurrentUser::getSettings();
        // dd($settings, $currentMode);
        if (isset($settings[$entity][$typeReport][$currentMode])) {
            $params = $settings[$entity][$typeReport][$currentMode];
            $params = self::removeNullItems($params);
            $defaultParams = $this->getDefaultValueParams($params, $request);
            $diffFields = array_diff( array_keys($defaultParams), array_keys($params));
            // dd($params, $defaultParams, $diffFields);
            
            if (Report::isNullParams($params) || empty($params) || count($params) === 1 || count($diffFields) > 0) {
                foreach ($defaultParams as $key => $value) {
                    // Customize for Mode on Parameter
                    if($key === 'children_mode') {
                        $childrenMode = $params['children_mode'] ?? $value;
                        if(isset($settings[$entity][$typeReport][$currentMode][$childrenMode])){
                            $params = array_merge($params, $settings[$entity][$typeReport][$currentMode][$childrenMode]);
                            // dd($params);
                        }
                    };
                    if(!isset($params[$key]) || !$params[$key]) {
                        $params[$key] = $value;
                    } 
                }
                // dump($params);
                return $params;
            }
            return $params;
        }
        return $this->getDefaultValueParams([], $request);
    }
}
