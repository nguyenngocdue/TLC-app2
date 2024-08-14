<?php

namespace App\View\Components\Reports2;


class TransferUserSettingReport2
{
    private static $instance = null;
    private function __construct() {}
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new TransferUserSettingReport2();
        }
        return self::$instance;
    }
    public function switchReport2($request, $settings)
    {
        $inputValue = $request->all();
        $entityType = $inputValue['entity_type'];
        $entityType2 = $inputValue['entity_type2'];
        $currentParamsRp = json_decode($inputValue['current_param_report'], true);
        $rpLinkId = $inputValue['current_report_link'];
        $currentRpId = $inputValue['report_id'];

        $ins = UserSettingReport2::getInstance($entityType2);
        $storesKeyFilter = $ins->getStoredFilterKeyUserSetting($currentRpId, $rpLinkId);
        if ($storesKeyFilter) {
            $currentParamsUser = $ins->getCurrentParamsUser($entityType, $storesKeyFilter);
            $currentParamsUser['current_report_link'] = (int)$rpLinkId;

            // update filter for report link
            foreach ($currentParamsRp as $key => $value) {
                if (is_null($value)) {
                    if (isset($currentParamUser[$key])) {
                        $currentParamsUser[$key] = $value;
                    }
                }
            }
            $settings[$entityType][$entityType2][$storesKeyFilter] = $currentParamsUser;
        }
        return $settings;
    }
}
