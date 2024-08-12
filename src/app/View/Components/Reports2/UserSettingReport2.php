<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_report;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;

class UserSettingReport2
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
            self::$instance = new UserSettingReport2($entityType2);
        }
        return self::$instance;
    }

    function getStoredFilterKeyUserSetting($rpId, $rpLinkId = null)
    {
        if (is_null($rpLinkId)) $rpLinkId = $rpId;
        $insRp = Rp_report::find($rpId)->getDeep()->getFilterModes;
        $storedFilterKey = $insRp->where('linked_to_report_id', $rpLinkId)->first()->stored_filter_key;
        return $storedFilterKey;
    }

    function getCurrentParamsUser($entityType, $storedFilterKey)
    {
        $settings = CurrentUser::getSettings();
        $entityType2 = $this->entityType2;
        $keys = [$entityType, $entityType2, $storedFilterKey];
        if (Report::checkKeysExist($settings, $keys)) {
            return  $settings[$entityType][$this->entityType2][$storedFilterKey];
        }
        return [];
    }

    function getNewUserSettings($settings, $entityType, $storesKeyFilter)
    {
        return $settings[$entityType][$this->entityType2][$storesKeyFilter];
    }
}
