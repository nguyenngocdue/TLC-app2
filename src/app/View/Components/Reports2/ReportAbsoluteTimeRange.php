<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\DateReport;
use App\Utils\Support\PresetsTimeRange;
use App\Utils\Support\ReportPreset;
use DateTime;
use Illuminate\View\Component;
use Illuminate\Support\Str;


class ReportAbsoluteTimeRange extends Component
{
    use TraitReportFilter;
    protected $reportType2 = "report2";
    public function __construct(
        private $report = null
    ) {}

    public function render()
    {
        $rp = $this->report;
        $currentParams = $this->currentParamsReport($rp);
        $presets = PresetsTimeRange::createPresets($currentParams);
        $timezoneData = DateReport::getTimeZones();

        $currentParams = $this->formatFromAndToDate($currentParams, 'Y-m-d H:i:s');

        return view(
            'components.reports2.report-absolute-time-range',
            [
                'rp' => $rp,
                'entityType' => $rp->entity_type,
                'reportType2' => $this->reportType2,
                'routeFilter' => route('report_filters' . '.update', $rp->id),
                'fromDate' => $currentParams['from_date'] ?? '',
                'toDate' => $currentParams['to_date'] ?? '',
                'presets' => $presets,
                'presetTitle' => $currentParams['preset_key'],
                'timezoneData' => $timezoneData,
                'timeZone' => $currentParams['time_zone'],
                'dateDisplayFormat' => $currentParams['date_display_format'] ?? 'y-m-d H:i:s',
            ]
        );
    }
}
