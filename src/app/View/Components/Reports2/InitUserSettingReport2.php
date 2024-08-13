<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_report;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Auth;

class InitUserSettingReport2
{
    private static $instance = null;
    protected $entityType2;

    private function __construct($entityType2)
    {
        $this->entityType2 = $entityType2;
    }
    public static function getInstance($entityType2)
    {
        if (self::$instance == null) {
            self::$instance = new InitUserSettingReport2($entityType2);
        }
        return self::$instance;
    }


    public function saveParamsToUser($entityType, $currentRpId, $keyFilterRpLinks)
    {
        $params = $this->createValueForParams($entityType, $keyFilterRpLinks, $currentRpId);

        $checkParams = $params;
        unset($checkParams[array_key_first($checkParams)]['current_report_link']);

        if (!empty(array_filter($checkParams[array_key_first($checkParams)]))) {
            $this->updateUserSettings($entityType, $params, $currentRpId);
            return $params;
        }
        return [];
    }



    public function createValueForParams($entityType, $keyFilterRpLinks, $currentRpId)
    {
        $filterRpLinks = Rp_report::find($currentRpId)->getDeep()->getFilterModes;
        $rp = $filterRpLinks->where('linked_to_report_id', $currentRpId)->first();
        $storedFilterKey = $rp->stored_filter_key; // return xxx
        $paramsNeedToSave = $keyFilterRpLinks[$storedFilterKey];

        $ins = UserSettingReport2::getInstance($this->entityType2);
        $storesKeyFilter = $ins->getStoredFilterKeyUserSetting($currentRpId);

        // has already saved into db
        $currentParamsUser = [];
        if ($storesKeyFilter) {
            $currentParamsUser = $ins->getCurrentParamsUser($entityType, $storesKeyFilter);
        }
        $paramsToSave = [];
        foreach ($paramsNeedToSave as $key => $filterDetail) {
            $defaultValue = ($x = $filterDetail->default_value) ? explode(',', $x) : $x;
            // set default value 
            if ($defaultValue && !isset($currentParamsUser[$key]) || $defaultValue && is_null($currentParamsUser[$key])) {
                $paramsToSave[$storedFilterKey][$key] = $defaultValue;
            }
            //  else {
            //     // set current value
            //     $paramsToSave[$storedFilterKey][$key] = $currentParamsUser[$key] ?? null;
            // }
        }
        $paramsToSave[$storedFilterKey]['current_report_link'] = $currentRpId;
        return $paramsToSave;
    }

    private function updateUserSettings($entityType, $params, $currentRpId)
    {
        $setting = CurrentUser::getSettings();
        $keys = [$entityType, $this->entityType2];
        $needsUpdate = false;

        if (Report::checkKeysExist($setting, $keys)) {
            $storedSettings = $setting[$entityType][$this->entityType2];
            $storedKey = key($params);

            if (isset($storedSettings[$storedKey]) && Report::arraysAreDifferent($storedSettings[$storedKey], end($params))) {
                $needsUpdate = true;
            }
        } else {
            $needsUpdate = true;
        }

        if ($needsUpdate) {
            $setting[$entityType][$this->entityType2] = $params;
            $user = User::find(Auth::id());
            $user->settings = $setting;

            if ($user->update()) {
                $needsUpdate = false;
                return toastr()->success("User settings initialized successfully.", "Success");
            } else {
                return toastr()->error("Failed to update user settings.", "Error");
            }
        }
    }



    private function createDefaultCurrentParams($filterDetails, $paramsUser)
    {
        $params = [];
        foreach ($filterDetails as $filter) {
            $filterName = Report::changeFieldOfFilter($filter);
            $val = $filter->default_value;
            $defaultValues = explode(',', $val);

            if (array_key_exists($filterName, $paramsUser)) {
                if (is_null($paramsUser[$filterName]) && $val) {
                    $paramsUser[$filterName] = (array)$defaultValues;
                }
            } elseif (empty($paramsUser)) {
                $params[$filterName] = $val;
            } elseif (!in_array($filterName, array_keys($paramsUser))) {
                // the 1st time click dropdown of report link
                $params[$filterName] = $val;
            }
        }
        $result = array_merge($paramsUser, $params);
        return $result;
    }

    function getCurrentParams($entityType, $reportId)
    {
        $ins = UserSettingReport2::getInstance($this->entityType2);
        $storesKeyFilter = $ins->getStoredFilterKeyUserSetting($reportId);
        if ($storesKeyFilter) {
            $currentParamsUser = $ins->getCurrentParamsUser($entityType, $storesKeyFilter);
            // dd($storesKeyFilter, $currentParamsUser);
            return $currentParamsUser;
        }
        return [$entityType][$this->entityType2][$storesKeyFilter];
    }
}
