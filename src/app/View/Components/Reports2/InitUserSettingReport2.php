<?php

namespace App\View\Components\Reports2;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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


    public function saveFirstParamsToUser($entityType, $currentRpId, $filterDetails, $paramsUrl)
    {
        $params = $this->getDefaultParams($entityType, $filterDetails, $currentRpId, $paramsUrl);
        // dump($params);
        if ($params) {
            $this->updateUserSettings($entityType, $currentRpId, $params);
            // Log::info(json_encode($params));
            return [
                'status' => 'success',
                'message' => 'Parameters saved successfully.',
            ];
        }
    }

    public function getDefaultParams($entityType, $filterDetails, $currentRpId, $paramsUrl)
    {
        $params = [];
        foreach ($filterDetails as $filter) {
            $filterName = Report::changeFieldOfFilter($filter);
            if (!empty($paramsUrl) && isset($paramsUrl[$filterName])) {
                $params[$filterName] = (array)$paramsUrl[$filterName];
            }
            $defaultValue = $filter->default_value;
            if ($defaultValue) {
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
        // dump($params);
        $userSetting = CurrentUser::getSettings();
        $userSetting[$entityType][$this->entityType2][$currentRpId] = $params;

        $user = User::find(Auth::id());
        $user->settings = $userSetting;
        $updateSuccess = $user->update();
        if ($updateSuccess) {
            return toastr()->success("Initialize User Settings Successfully", "Successfully");
        } else {
            return toastr()->error("Failed to Initialize User Settings", "Error");
        }
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
