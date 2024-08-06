<?php

namespace App\View\Components\Reports2;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Auth;

trait TraitInitUserSettingReport2
{

    function saveFirstParamsToUser($entityType, $currentRpId, $filterDetails)
    {
        $params = $this->getDefaultParams($entityType, $filterDetails, $currentRpId);

        if ($params) {
            $this->updateUserSettings($entityType, $currentRpId, $params);
            toastr()->success('Init User Settings Successfully', 'Successfully');
            return redirect()->back();
        }
    }

    private function getDefaultParams($entityType, $filterDetails, $currentRpId)
    {
        $params = [];
        foreach ($filterDetails as $filter) {
            $defaultValue = $filter->default_value;
            if ($defaultValue) {
                $filterName = Report::changeFieldOfFilter($filter);
                $userSetting = CurrentUser::getSettings();
                $paramsInUser = $userSetting[$entityType][$this->entityType2][$currentRpId] ?? [];
                if (!array_key_exists($filterName, $paramsInUser) || is_null($paramsInUser[$filterName])) {
                    $params[$filterName] = explode(',', $defaultValue);
                }
            }
        }

        return $params;
    }

    private function updateUserSettings($entityType, $currentRpId, $params)
    {
        $userSetting = CurrentUser::getSettings();
        $userSetting[$entityType][$this->entityType2][$currentRpId] = $params;

        $user = User::find(Auth::id());
        $user->settings = $userSetting;
        $user->update();
    }

    private function createDefaultCurrentParams($filterDetails, $paramsUser)
    {
        $params = [];
        foreach ($filterDetails as $filter) {
            $filterName = str_replace('_name', '_id', $filter->getColumn->data_index);
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

    function getCurrentParams($entityType, $reportId, $filterDetails)
    {
        $paramsUser = [];
        $keys = [$entityType, $this->entityType2, $reportId];
        $userSetting = CurrentUser::getSettings();
        if (Report::nestedKeysExist($userSetting, $keys)) {
            $paramsUser = $userSetting[$entityType][$this->entityType2][$reportId];
        }
        $currentParams = $this->createDefaultCurrentParams($filterDetails, $paramsUser);
        $currentParams["current_report_link"] = $reportId;
        return $currentParams;
    }
}
