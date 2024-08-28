<?php

namespace App\View\Components\Reports2;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InitUserSettingReport2
{
    private static $instance = null;
    protected $reportType2;

    private function __construct($reportType2)
    {
        $this->reportType2 = $reportType2;
    }
    public static function getInstance($reportType2 = null)
    {
        if (self::$instance == null) {
            self::$instance = new InitUserSettingReport2($reportType2);
        }
        return self::$instance;
    }

    private function createDefaultParams($rpFilters)
    {
        $params = [];
        foreach ($rpFilters as $filter) {
            if(!$filter->is_active) continue;
            $defaultVal = $filter->default_value;
            if ($defaultVal) {
                $params[$filter->data_index] = $filter->is_multiple ? explode(",", $defaultVal) : $defaultVal;
            } else {
                $params[$filter->data_index] = $defaultVal;
            }
        }
        // $params['time_zone'] = 'Asia/Bangkok';
        return $params;
    }
    

    public static function getParamsToUpdate($paramsInConfig, $paramsInUser)
    {
        $paramsToUpdate = [];
        $keyParamsInUser = array_keys($paramsInUser);
        try {
            foreach ($paramsInConfig as $key => $value) {
                if (in_array($key, $keyParamsInUser)) {
                    if(is_null($paramsInUser[$key]) && is_null($value)) continue;
                    if (!is_null($paramsInUser[$key]) && !is_null($paramsInUser[$key][0]) ) continue;
                    else {
                        $paramsToUpdate[$key] = $value;
                    }
                } 
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        // dd($paramsInConfig, $paramsInUser, $paramsToUpdate);
        return $paramsToUpdate;
    }
    

    function updateUserSettingRp($settings) {
        $user = User::find(Auth::id());
        $user->settings = $settings;
        $u = $user->update();
        // if ($u) toastr()->success('Due: User Settings Saved Successfully', 'Successfully');
    }

    public function initParamsUserSettingRp($rpId, $entityType, $rpFilterLinks, $rpFilters){
        $settings = CurrentUser::getSettings();
        
        $storedFilterKey = Report::getStoredFilterKey($rpId,$rpFilterLinks);
        
        // create default values in the database -> in case that the reports were previously saved in user_setting
        $keys = [$entityType, $this->reportType2, $storedFilterKey];
        $isSave = false;
        if (Report::checkKeysExist($settings, $keys)) {
            $paramsInConfig = $this->createDefaultParams($rpFilters);
            $paramsInUser = $settings[$entityType][$this->reportType2][$storedFilterKey];
            $paramsToUpdate = self::getParamsToUpdate($paramsInConfig, $paramsInUser, true);
            // dd($paramsInConfig, $paramsToUpdate);
            
            if(!empty($paramsToUpdate)) {
                $paramsToUpdate = array_merge($paramsInUser, $paramsToUpdate);
                $settings[$entityType][$this->reportType2][$storedFilterKey] = $paramsToUpdate;
                $isSave = True;
            }
        } else {
            // create default values in the database -> in case that the reports weren't saved in user_setting
            $defaultParams = $this->createDefaultParams($rpFilters);
            $settings[$entityType][$this->reportType2][$storedFilterKey] = $defaultParams;
            
            $isSave = True;    
        }
        if($isSave) {
            Log::info($isSave);
            self::updateUserSettingRp($settings);
            $isSave = False;    
        }
        $params = $settings[$entityType][$this->reportType2][$storedFilterKey] ?? [];

        if(!isset($params['time_zone'])){
            $params['time_zone'] = 'Asia/Bangkok';
        }
        if(!isset($params['pro_set_title'])){
            $params['pro_set_title'] = 'Time Range';
        }
        return  $params;
    }
}
