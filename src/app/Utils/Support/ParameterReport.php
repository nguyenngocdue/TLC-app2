<?php

namespace App\Utils\Support;

use App\Models\Attachment;

class ParameterReport
{
    public static function getConfigByName($name = "report"){
        $uri = $_SERVER['REQUEST_URI'];
        $reportName =last(explode('/',$uri));
        $configData = config($name.".".$reportName)?? [];
        return $configData;
    }
    public static function getTargetIds($configData){
        if($configData){
            if(isset($configData['white_list']) && !empty($configData['white_list'])){
                $targetIds['white_list'] = $configData['white_list'];
            } 
            if(isset($configData['black_list']) && !empty($configData['black_list']))
            {
                $targetIds['black_list'] = $configData['black_list'];
            }
        }
        return $targetIds ?? [];
    }

    public static function getDBParameter($targetIds, $modelName, $isArray = false){
        $modelPath = "App\Models\\".$modelName;
        if(isset($targetIds['black_list'])){
            $excludedIds = $targetIds['black_list'];
            $list = $modelPath::whereNull('deleted_at')
                                    ->whereNotIn('id',$excludedIds);

        } elseif (isset($targetIds['white_list'])){
            $list = $modelPath::whereNull('deleted_at')
                                    ->whereIn('id',$targetIds);
        } else{
            $list = $modelPath::whereNull('deleted_at');
        }

        $list = $isArray ? $list->get()->toArray() : $list->get();
        return $list ?? [];
    }
}
