<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_report;
use App\Utils\Support\Report;

class TransferUserSettingReport2
{
    private static $instance = null;
    private function __construct()
    {
    }
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
        $paramsCurrentRp = (array)json_decode($inputValue['params_current_report']);
        $reportLinkId = $inputValue['current_report_link'];
        $currentRpId = $inputValue['report_id'];

        $insRp = Rp_report::find((int)$reportLinkId)->getDeep();
        $filterDetailsRpLink = $insRp->getFilterDetails;

        $keys = [$entityType, $entityType2, $reportLinkId];
        if (Report::nestedKeysExist($settings, $keys)) {
            $settings = $this->updateExistingReport($settings, $filterDetailsRpLink, $paramsCurrentRp, $entityType, $entityType2, $reportLinkId);
        } else {
            $settings = $this->initializeReportLink($settings, $filterDetailsRpLink, $paramsCurrentRp, $entityType, $entityType2, $reportLinkId);
        }
        $settings[$entityType][$entityType2][$currentRpId]['current_report_link'] = (string)$reportLinkId;
        return $settings;
    }

    private function updateExistingReport($settings, $filterDetailsRpLink, $paramsCurrentRp, $entityType, $entityType2, $reportLinkId)
    {
        foreach ($filterDetailsRpLink as $filter) {
            $filterName = Report::changeFieldOfFilter($filter);
            if (array_key_exists($filterName, $paramsCurrentRp)) {
                $settings[$entityType][$entityType2][$reportLinkId][$filterName] = !empty($paramsCurrentRp[$filterName]) ?
                    $paramsCurrentRp[$filterName] :
                    Report::getDefaultValuesFilterByFilterDetail($filterDetailsRpLink)[$filterName];
            }
        }
        $settings[$entityType][$entityType2][$reportLinkId]['current_report_link'] = (string)$reportLinkId;

        return $settings;
    }

    private function initializeReportLink($settings, $filterDetailsRpLink, $paramsCurrentRp, $entityType, $entityType2, $reportLinkId)
    {
        foreach ($filterDetailsRpLink as $filter) {
            $filterName = Report::changeFieldOfFilter($filter);
            if (array_key_exists($filterName, $paramsCurrentRp) && !empty($paramsCurrentRp[$filterName])) {
                $settings[$entityType][$entityType2][$reportLinkId][$filterName] = $paramsCurrentRp[$filterName];
            }
        }
        $settings[$entityType][$entityType2][$reportLinkId]['current_report_link'] = (string)$reportLinkId;

        return $settings;
    }
}
