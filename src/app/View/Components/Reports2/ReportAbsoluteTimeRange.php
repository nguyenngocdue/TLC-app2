<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use DateTime;
use Exception;
use Illuminate\View\Component;
use Illuminate\Support\Str;


class ReportAbsoluteTimeRange extends Component
{
    use TraitReportFilter;

    protected $reportType2 = 'report2';
    public function __construct(
        private $report = null
    ){}

    private function generateDateRange($timeFrame, $toDate, $fromDate) {
        switch ($timeFrame) {
            case 'today':
                $fromDate->setTime(0, 0, 0); // Start of today
                $toDate->setTime(23, 59, 59); // End of today
                break;
            case 'yesterday':
                $fromDate->modify('-1 day');
                $toDate->modify('-1 day')->setTime(23, 59, 59); // End of yesterday
                $fromDate->setTime(0, 0, 0); // Start of yesterday
                break;
            case 'last_2_days':
                $fromDate->modify('-2 days')->setTime(0, 0, 0); // Start of 2 days ago
                $toDate->setTime(23, 59, 59); // End of today
                break;
            case 'last_week':
                $fromDate->modify('last week')->setTime(0, 0, 0); // Start of last week
                $toDate->modify('last week +6 days')->setTime(23, 59, 59); // End of last week
                break;
            case 'last_month':
                $fromDate->modify('first day of last month')->setTime(0, 0, 0); // Start of last month
                $toDate->modify('last day of last month')->setTime(23, 59, 59); // End of last month
                break;
            case 'last_year':
                $fromDate->modify('first day of January last year')->setTime(0, 0, 0); // Start of last year
                $toDate->modify('last day of December last year')->setTime(23, 59, 59); // End of last year
                break;
            case 'last_5_minutes':
                $fromDate->modify('-5 minutes'); // 5 minutes ago
                break;
            case 'last_15_minutes':
                $fromDate->modify('-15 minutes'); // 15 minutes ago
                break;
            case 'last_30_minutes':
                $fromDate->modify('-30 minutes'); // 30 minutes ago
                break;
            case 'last_1_hour':
                $fromDate->modify('-1 hour'); // 1 hour ago
                break;
            case 'last_3_hours':
                $fromDate->modify('-3 hours'); // 3 hours ago
                break;
            case 'last_6_hours':
                $fromDate->modify('-6 hours'); // 6 hours ago
                break;
            case 'last_12_hours':
                $fromDate->modify('-12 hours'); // 12 hours ago
                break;
            default:
                throw new Exception('Invalid timeframe specified.');
        }
    
        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s'),
        ];
    }
    
    

    private function createPresets() {
        $currentParams = $this->currentParamsReport();
        $timezone = $currentParams['time_zone'];
        $timeAsNumber = DateReport::getUtcOffset($timezone);
        $toDate = new DateTime();
        $toDate->modify("{$timeAsNumber} hours");

        $presets = [
            // Already provided presets
            'today' => $this->generateDateRange('today', clone $toDate, clone $toDate),
            'yesterday' => $this->generateDateRange('yesterday', clone $toDate, clone $toDate),
            'last_2_days' => $this->generateDateRange('last_2_days', clone $toDate, clone $toDate),
            'last_week' => $this->generateDateRange('last_week', clone $toDate, clone $toDate),
            'last_month' => $this->generateDateRange('last_month', clone $toDate, clone $toDate),
            'last_year' => $this->generateDateRange('last_year', clone $toDate, clone $toDate),
            'last_5_minutes' => $this->generateDateRange('last_5_minutes', clone $toDate, clone $toDate),
            'last_15_minutes' => $this->generateDateRange('last_15_minutes', clone $toDate, clone $toDate),
            'last_30_minutes' => $this->generateDateRange('last_30_minutes', clone $toDate, clone $toDate),
            'last_1_hour' => $this->generateDateRange('last_1_hour', clone $toDate, clone $toDate),
            'last_3_hours' => $this->generateDateRange('last_3_hours', clone $toDate, clone $toDate),
            'last_6_hours' => $this->generateDateRange('last_6_hours', clone $toDate, clone $toDate),
            'last_12_hours' => $this->generateDateRange('last_12_hours', clone $toDate, clone $toDate),
        
            // // New presets based on your request
            // 'today_so_far' => $this->generateDateRange('today_so_far', $this->getStartOfDay(), clone $toDate),
            // 'this_week' => $this->generateDateRange('this_week', $this->getStartOfWeek(), clone $toDate),
            // 'this_week_so_far' => $this->generateDateRange('this_week_so_far', $this->getStartOfWeek(), clone $toDate),
            // 'this_month' => $this->generateDateRange('this_month', $this->getStartOfMonth(), clone $toDate),
            // 'this_month_so_far' => $this->generateDateRange('this_month_so_far', $this->getStartOfMonth(), clone $toDate),
            // 'this_year' => $this->generateDateRange('this_year', $this->getStartOfYear(), clone $toDate),
            // 'this_year_so_far' => $this->generateDateRange('this_year_so_far', $this->getStartOfYear(), clone $toDate),
            // 'this_fiscal_quarter' => $this->generateDateRange('this_fiscal_quarter', $this->getStartOfFiscalQuarter(), clone $toDate),
            // 'this_fiscal_quarter_so_far' => $this->generateDateRange('this_fiscal_quarter_so_far', $this->getStartOfFiscalQuarter(), clone $toDate),
            // 'this_fiscal_year' => $this->generateDateRange('this_fiscal_year', $this->getStartOfFiscalYear(), clone $toDate),
            // 'this_fiscal_year_so_far' => $this->generateDateRange('this_fiscal_year_so_far', $this->getStartOfFiscalYear(), clone $toDate),
        
            // // Other possible new presets
            // 'last_fiscal_year' => $this->generateDateRange('last_fiscal_year', $this->getStartOfFiscalYear(-1), $this->getEndOfFiscalYear(-1)),
            // 'last_fiscal_quarter' => $this->generateDateRange('last_fiscal_quarter', $this->getStartOfFiscalQuarter(-1), $this->getEndOfFiscalQuarter(-1)),
            // 'this_quarter' => $this->generateDateRange('this_quarter', $this->getStartOfQuarter(), clone $toDate),
            // 'this_quarter_so_far' => $this->generateDateRange('this_quarter_so_far', $this->getStartOfQuarter(), clone $toDate),
        ];
        
        return $presets;
    }

    private function createFromDateToDateForHTML($currentParams){
        $fromDate = $currentParams['from_date'] ?? null;
        $toDate = $currentParams['to_date'] ?? null;
        $timeZone = $currentParams['time_zone'] ?? null;
        if ($timeZone){
            $timeAsNumber = DateReport::getUtcOffset($timeZone);
            if ($fromDate) {
                $fromDate = DateReport::convertToTimezone($fromDate, $timeAsNumber);
            }
            if ($toDate) {
                $toDate = DateReport::convertToTimezone($toDate, $timeAsNumber);
            }
        }
        return [$fromDate, $toDate];
    }

        
    public function render()
    {
        $rp = $this->report;
        $currentParams = $this->currentParamsReport();
        $presets = $this->createPresets(); 
        $timezoneData = DateReport::getTimeZones();

        [$fromDate, $toDate] = $this->createFromDateToDateForHTML($currentParams);

        return view('components.reports2.report-absolute-time-range', 
        [
            'rp' => $rp,
            'entityType' => $rp->entity_type,
            'reportType2' => $this->reportType2,
            'routeFilter' => route('report_filters' . '.update', $rp->id),
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'presets' => $presets,
            'presetTitle' => $currentParams['preset_title'],
            'timezoneData' => $timezoneData,
            'timeZone' => $currentParams['time_zone'],
        ]
    );
    }
}
