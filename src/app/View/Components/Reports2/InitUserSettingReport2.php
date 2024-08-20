<?php

namespace App\View\Components\Reports2;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InitUserSettingReport2
{
    private static $instance = null;
    protected $entityType2;

    private function __construct($entityType2)
    {
        $this->entityType2 = $entityType2;
    }
    public static function getInstance($entityType2 = null)
    {
        if (self::$instance == null) {
            self::$instance = new InitUserSettingReport2($entityType2);
        }
        return self::$instance;
    }

    private function createDefaultParams($rpFilters)
    {
        $params = [];
        foreach ($rpFilters as $filter) {
            if(!$filter->is_active) continue;
            $defaultVal = $filter->default_value;
            $dataIndex = $filter->is_multiple ? Str::plural($filter->data_index) : $filter->data_index;
            if ($defaultVal) {
                $params[$dataIndex] = $filter->is_multiple ? explode(",", $defaultVal) : $defaultVal;
            } else {
                $params[$dataIndex] = $defaultVal;
            }
        }
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
        // dump($paramsInConfig, $paramsToUpdate);
        return $paramsToUpdate;
    }
    

    function updateUserSettingRp($settings) {
        $user = User::find(Auth::id());
        $user->settings = $settings;
        $u = $user->update();
        // dump($u);
        if ($u) toastr()->success('Due: User Settings Saved Successfully', 'Successfully');
    }

    public function initParamsUserSettingRp($reportId, $entityType, $filterLinkDetails, $rpFilters){
        $settings = CurrentUser::getSettings();
        $storedFilterKey = $filterLinkDetails->first()?->getFilterLink->stored_filter_key ?? (string)$reportId;
        
        // create default values in the database -> in case that the reports were previously saved in user_setting
        $keys = [$entityType, $this->entityType2, $storedFilterKey];
        $isSave = false;
        if (Report::checkKeysExist($settings, $keys)) {
            $paramsInConfig = $this->createDefaultParams($rpFilters);
            $paramsInUser = $settings[$entityType][$this->entityType2][$storedFilterKey];
            $paramsToUpdate = self::getParamsToUpdate($paramsInConfig, $paramsInUser, true);
            // dd($paramsInConfig, $paramsToUpdate);
            if(!empty($paramsToUpdate)) {
                $paramsToUpdate = array_merge($paramsInUser, $paramsToUpdate);
                $settings[$entityType][$this->entityType2][$storedFilterKey] = $paramsToUpdate;
                $isSave = True;
            }
        } else {
            // create default values in the database -> in case that the reports weren't saved in user_setting
            $defaultParams = $this->createDefaultParams($rpFilters);
            $settings[$entityType][$this->entityType2][$storedFilterKey] = $defaultParams;
            
            $isSave = True;    
        }
        // dump($isSave);
        if($isSave) {
            // dd($isSave, $settings);
            self::updateUserSettingRp($settings);
            $isSave = False;    
        }
        $params = $settings[$entityType][$this->entityType2][$storedFilterKey] ?? [];
        return  $params;
    }
}
