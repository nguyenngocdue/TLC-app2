<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_report;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class FilterReport extends Component
{

    protected $entityType2 = 'report2';
    public $refreshPage = false;

    public function __construct(
        private $report = "",
        private $paramsUrl = [],

    ) {}

    private function getWarningFilters($filterDetails, $currentParams)
    {
        $warningFilters = [];
        foreach ($filterDetails as $filter) {
            if ($filter->is_required) {
                $column =  Report::changeFieldOfFilter($filter);
                if (empty($currentParams[$column])) {
                    $warningFilters[] = $filter->getColumn->title;
                }
            }
        }
        return $warningFilters;
    }

    private function getFilterKeyRpLinks($filterRpLinks, $filterDetails)
    {
        $dataIndexes = [];
        foreach ($filterRpLinks as $rpLink) {
            $rpLinkId = $rpLink->linked_to_report_id;
            $storedFilterKey = is_null($x = $rpLink->stored_filter_key) ? $rpLinkId : $x;
            $filterDetails = Rp_report::find($rpLinkId)->getDeep()->getFilterDetails;
            foreach ($filterDetails  as $filter) {
                $column = $filter->getColumn->data_index;
                if (isset($dataIndexes[$storedFilterKey])) {
                    if (!in_array($column, $dataIndexes[$storedFilterKey])) {
                        $dataIndexes[$storedFilterKey][$column] = $filter;
                    }
                } else {
                    $dataIndexes[$storedFilterKey] = [];
                }
            }
        }
        return $dataIndexes;
    }

    public function render()
    {
        $rp = (object)$this->report;

        $currentRpId = $rp->id;
        $rpName = $rp->name;
        $entityType = $rp->entity_type;
        $entityType2 = $this->entityType2;

        $filterRpLinks = $rp->getFilterModes;
        $filterDetails = $rp->getFilterDetails;
        $keyFilterRpLinks = $this->getFilterKeyRpLinks($filterRpLinks, $filterDetails);



        // // // check and set report_id if the current report is already stored in the db
        // $userSetting = CurrentUser::getSettings();
        // $keys = [$entityType, $entityType2, $currentRpId];
        // if (Report::nestedKeysExist($userSetting, $keys)) { # => has previously been opened and saved into user_setting
        //     $paramsInUser = $userSetting[$entityType][$entityType2][$currentRpId];
        //     $currentRpId = $paramsInUser['current_report_link'] ?? $currentRpId;
        // }


        // get filter detail from current report
        // $reportLink = Rp_report::find((int)$currentRpId)->getDeep();
        // $filterDetails = $reportLink->getFilterDetails->sortBy('order_no');

        // Save initial parameters to set default values when you open for the first time.
        $ins =  InitUserSettingReport2::getInstance($this->entityType2);
        $paramsInit = $ins->saveParamsToUser($entityType, $currentRpId, $keyFilterRpLinks, $this->paramsUrl);

        // create params from 'user_setting' and default value
        $currentParams = $ins->getCurrentParams($entityType, $currentRpId, $filterDetails);

        // Case: The 'currentParams' didn't reset after updating' user_setting'
        if (empty($currentParams)) $currentParams =  reset($paramsInit);


        // create data to render dropdown of report link
        $dataDropdownRpLink = collect($filterRpLinks)->mapWithKeys(function ($filterMode) {
            $linkedToRpId = Rp_report::find($filterMode->linked_to_report_id)->id ?? null;
            if (is_null($linkedToRpId)) return [];
            return [$linkedToRpId => $filterMode->name];
        });
        $warningFilters = $this->getWarningFilters($filterDetails, $currentParams);

        return view('components.reports2.filter-report', [
            'entityType' => $entityType,
            'reportName' => $rpName,
            'entityType2' => $entityType2,
            'filterDetails' => $filterDetails,
            'entity_type' => $entityType,
            'reportId' => $rp->id,
            'currentParams' => $currentParams,
            'routeFilter' => route('filter_report.update', $rp->id),
            'dataDropdownRpLink' => $dataDropdownRpLink,
            'filterModes' => $filterRpLinks,
            'refreshPage' => $this->refreshPage,
            'warningFilters' => $warningFilters,
        ]);
    }
}


/*
    Case 1
    https://127.0.0.1:38002/dashboard/rp_reports/34?project_id=5&sub_project_id=82&prod_order_id=50&prod_routing_id=2
    Case 2
    https://127.0.0.1:38002/dashboard/rp_reports/34?sub_project_id[]=5&sub_project_id[]=6&sub_project_id[]=7
*/