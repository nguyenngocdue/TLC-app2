<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_report;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class FilterReport extends Component
{
    use TraitInitUserSettingReport2;

    protected $entityType2 = 'report2';
    public function __construct(
        private $report = "",
        private $filterModes = [],
        private $filterDetails = [],

    ) {
    }

    public function render()
    {
        $report = (object)$this->report;
        $currentRpId = $report->id;
        $reportName = $report->name;
        $entityType = $report->entity_type;
        $entityType2 = $this->entityType2;

        // check and set report_id if the current report is already stored in the db
        $userSetting = CurrentUser::getSettings();
        $keys = [$entityType, $entityType2, $currentRpId];
        if (Report::nestedKeysExist($userSetting, $keys)) { # => has previously been opened and saved into user_setting
            $paramsInUser = $userSetting[$entityType][$entityType2][$currentRpId];
            $currentRpId = $paramsInUser['current_report_link'] ?? $currentRpId;
        }

        $filterModes = collect($this->filterModes);
        $reportLink = Rp_report::find((int)$currentRpId)->getDeep();
        // get filter detail from current report
        $filterDetails = $reportLink->getFilterDetails;
        // Save initial parameters to set default values when you open for the first time.
        $this->saveFirstParamsToUser($entityType, $currentRpId, $filterDetails);

        // create params from user_setting and default value
        $currentParams = $this->getCurrentParams($entityType, $currentRpId, $filterDetails);
        // dump($currentParams, $currentRpId);

        // create data to render dropdown of report link
        $dataDropdownRpLink = $filterModes->mapWithKeys(function ($filterMode) {
            $linkedToRpId = Rp_report::find($filterMode->linked_to_report_id)->id;
            return [$linkedToRpId => $filterMode->name];
        });

        return view('components.reports2.filter-report', [
            'entityType' => $entityType,
            'reportName' => $reportName,
            'entityType2' => $entityType2,
            'filterDetails' => $filterDetails,
            'entity_type' => $entityType,
            'reportId' => $report->id,
            'currentParams' => $currentParams,
            'routeFilter' => route('filter_report.update', $report->id),
            'dataDropdownRpLink' => $dataDropdownRpLink,
        ]);
    }
}
