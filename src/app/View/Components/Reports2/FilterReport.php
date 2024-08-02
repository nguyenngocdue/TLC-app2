<?php

namespace App\View\Components\Reports2;

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
    private function createDefaultCurrentParams($filterDetails, $currentParams)
    {
        foreach ($filterDetails as $filter) {
            $filterName = str_replace('_name', '_id', $filter->getColumn->data_index);
            $currentParams[$filterName] = $currentParams[$filterName] ?? (
                is_null($filter->default_value)
                ? null
                : '[' . '"' . implode('","', explode(',', $filter->default_value)) . '"' . ']'
            );
        }
        return $currentParams;
    }

    public function render()
    {
        $report = (object)$this->report;
        $reportName = $report->name;
        $filterModes = collect($this->filterModes);
        $filterDetails = $this->filterDetails;

        $keyNameModes = $filterModes->mapWithKeys(function ($filterMode) {
            return [$filterMode->name => $filterMode->name];
        });

        $settingUser = CurrentUser::getSettings();
        $paramIndex = isset($settingUser[$reportName]) ?  $settingUser[$reportName] : [];
        $currentMode =  isset($paramIndex['current_mode']) || is_null($paramIndex['current_mode'])  ? 'Mode 1' :  $paramIndex['current_mode'];
        $currentParams = isset($paramIndex[$currentMode]) ? $paramIndex[$currentMode] : [];
        $currentParams = $this->createDefaultCurrentParams($filterDetails, $currentParams);
        // dd($currentParams);

        return view('components.reports2.filter-report', [
            'keyNameModes' => $keyNameModes,
            'filterDetails' => $filterDetails,
            'entity_type' => "not yet",
            'reportName' => $reportName,
            'params' => $currentParams,
        ]);
    }
}
