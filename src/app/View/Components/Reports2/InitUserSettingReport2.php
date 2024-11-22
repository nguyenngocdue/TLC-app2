<?php

namespace App\View\Components\Reports2;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use App\Utils\Support\DefaultValueReport;
use App\Utils\Support\Report;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
                    if (!is_null($paramsInUser[$key])) {
                        if (is_array($paramsInUser[$key]) && !is_null($paramsInUser[$key][0]) ) continue;
                    } 
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
        // if ($u) toastr()->success('User Settings Saved Successfully', 'Successfully');
    }

    private function  createDefaultValueWeekOfYear($params,$rpFilters) {
        $date = new DateTime('now');
        $currentWeek = $date->format('W'); 
        foreach($rpFilters as $value){
            if ($value->data_index === 'week_of_year') {
                $currentWeek = ($x = $value->default_value) ?  substr($x, 0, strpos($x, '_')) : $currentWeek;
                break;
            }
        }
        $currentWeek = $currentWeek > 0 && $currentWeek < 53 ? $currentWeek : $currentWeek;
        $weeksOfYearNum = $currentWeek.'_' . date('Y');
        $dates = DateReport::getWeekOfYearData();
        $date = $dates[$weeksOfYearNum];
        [$toDate1, $toDate2] = [ $date->last_time->to_date, $date->this_time->to_date];
        $params['last_time_to_date'] = $toDate1;
        $params['this_time_to_date'] = $toDate2;
        $params['year'] =  $date->this_time->year;
        $params['week_number'] = $currentWeek;
        $params['week_of_year'] =  $weeksOfYearNum;
        return $params;
    }

    public function initParamsUserSettingRp($rp, $entityType, $rpFilterLinks, $rpFilters){
        $settings = CurrentUser::getSettings();
        
        $storedFilterKey = Report::getStoredFilterKey($rp->id,$rpFilterLinks);
        
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
            $params['time_zone'] = 'UTC+7';
        }
        if(!isset($params['preset_title'])){
            $params['preset_title'] = 'Time Range';
        }
        if (!isset($params['week_number'])){
            $params = $this->createDefaultValueWeekOfYear($params, $rpFilters);
            $settings[$entityType][$this->reportType2][$storedFilterKey] = $params;
            self::updateUserSettingRp($settings);
        }
        
        
        // set default value for Time Range
        if ($rp->has_time_range) {
            if(!isset($params['from_date']) || !isset($params['to_date']) || !$params['from_date'] || !$params['from_date'] ){
                $params = DefaultValueReport::updateDefaultValueFromDateToDate($params, $rp);
                $settings[$entityType][$this->reportType2][$storedFilterKey] = $params;
                self::updateUserSettingRp($settings);

            }
        }
        return  $params;
    }
}
