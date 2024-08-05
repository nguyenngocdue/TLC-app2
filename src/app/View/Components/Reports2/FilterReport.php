<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_report;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class FilterReport extends Component
{
    public function __construct(
        private $report = "",
        private $filterModes = [],
        private $filterDetails = [],

    ) {
    }
    private function createDefaultCurrentParams($filterDetails, $currentParams, $reportName)
    {
        foreach ($filterDetails as $filter) {
            $filterName = str_replace('_name', '_id', $filter->getColumn->data_index);
            $currentParams[$filterName] = $currentParams[$filterName] ?? (
                is_null($filter->default_value)
                ? null
                : explode(',', $filter->default_value));
        }
        if (!isset($currentParams['current_mode'])) {
            $currentParams['current_mode'] = $reportName;
        }
        return $currentParams;
    }

    public function render()
    {
        $report = (object)$this->report;
        $reportName = $report->name;
        $reportId = $report->id;
        $filterModes = collect($this->filterModes);
        $filterDetails = $this->filterDetails;

        $reportAccesses = $filterModes->mapWithKeys(function ($filterMode) {
            $reportAccess = Rp_report::find($filterMode->report_access_id)->name;
            return [$reportAccess => $filterMode->name];
        });

        $settingUser = CurrentUser::getSettings();
        $paramIndex = isset($settingUser[$reportName]) ?  $settingUser[$reportName] : [];

        $currentMode =  isset($paramIndex['current_mode'])  ? (is_null($x = $paramIndex['current_mode']) ? $reportName :  $x) : $reportName;


        $currentParams = isset($paramIndex[$currentMode]) ? $paramIndex[$currentMode] : [];
        $currentParams = $this->createDefaultCurrentParams($filterDetails, $currentParams, $reportName);

        $modeData = isset($paramIndex[$currentMode]) ? $paramIndex[$currentMode] : [
            'report_name' => $reportName,
            'current_mode' => $currentMode
        ];
        // dd($currentParams);

        $routeFilter = route('filter_report.update', $reportId);
        return view('components.reports2.filter-report', [
            'reportAccesses' => $reportAccesses,
            'filterDetails' => $filterDetails,
            'entity_type' => "not yet",
            'reportName' => $reportName,
            'params' => $currentParams,
            'routeFilter' => $routeFilter,
            'modeData' => $modeData
        ]);
    }
}
