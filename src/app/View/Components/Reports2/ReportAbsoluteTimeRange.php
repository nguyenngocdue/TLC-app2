<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use App\Utils\Support\ReportPreset;
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
                $toDate->modify('-1 day')->setTime(23, 59, 59); 
                $fromDate->setTime(0, 0, 0); 
                break;
            case 'last_2_days':
                $fromDate->modify('-2 days')->setTime(0, 0, 0); 
                $toDate->setTime(23, 59, 59); 
                break;
            case 'last_week':
                $fromDate->modify('last week')->setTime(0, 0, 0); 
                $toDate->modify('last week +6 days')->setTime(23, 59, 59); 
                break;
            case 'last_month':
                $fromDate->modify('first day of last month')->setTime(0, 0, 0); 
                $toDate->modify('last day of last month')->setTime(23, 59, 59); 
                break;
            case 'last_year':
                $fromDate->modify('first day of January last year')->setTime(0, 0, 0); 
                $toDate->modify('last day of December last year')->setTime(23, 59, 59); 
                break;
            case 'last_2_years':
                $fromDate->modify('first day of January')->modify('-2 years')->setTime(0, 0, 0);
                $toDate->modify('last day of December last year')->setTime(23, 59, 59);
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
                $fromDate = $fromDate;
        }
    
        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s'),
        ];
    }
    
    

    private function createPresets() {
        $currentParams = $this->currentParamsReport();
        $timezone = $currentParams['time_zone'];
        $utcOffset = DateReport::getUtcOffset($timezone);
        $toDate = new DateTime();
        $toDate->modify("{$utcOffset} hours");
 
        $presets = [

            // // New presets based on your request
            'today_so_far' => ReportPreset::getDateOfToday($timezone, clone $toDate),
            'this_week' => ReportPreset::getDateOfThisWeek($timezone),
            'this_week_so_far' => ReportPreset::getDateOfThisWeek($timezone, clone $toDate),

            'this_month' => ReportPreset::getDateOfThisMonth($timezone),
            'this_month_so_far' =>  ReportPreset::getDateOfThisMonth($timezone, clone $toDate),
            
            'this_year' => ReportPreset::getDateOfThisYear($timezone),
            'this_year_so_far' => ReportPreset::getDateOfThisYear($timezone, clone $toDate),
            
            'first_half_year' => ReportPreset::getDateOfHalfYear('first_half', $timezone),
            'second_half_year' => ReportPreset::getDateOfHalfYear('second_half', $timezone),

            'this_quarter' => ReportPreset::getDateThisQuarter($timezone),
            'this_quarter_so_far' => ReportPreset::getDateThisQuarter($timezone, clone $toDate),

            'first_quarter' => ReportPreset::getDateForQuarter(1, $timezone),
            'second_quarter' => ReportPreset::getDateForQuarter(2, $timezone),
            'third_quarter' => ReportPreset::getDateForQuarter(3, $timezone),
            'fourth_quarter' => ReportPreset::getDateForQuarter(4, $timezone),


            // Already provided presets
            'today' => $this->generateDateRange('today', clone $toDate, clone $toDate),
            'yesterday' => $this->generateDateRange('yesterday', clone $toDate, clone $toDate),
            'last_2_days' => $this->generateDateRange('last_2_days', clone $toDate, clone $toDate),
            'last_week' => $this->generateDateRange('last_week', clone $toDate, clone $toDate),
            'last_month' => $this->generateDateRange('last_month', clone $toDate, clone $toDate),
            'last_year' => $this->generateDateRange('last_year', clone $toDate, clone $toDate),
            'last_2_years' => $this->generateDateRange('last_2_years', clone $toDate, clone $toDate),
        
            'last_5_minutes' => $this->generateDateRange('last_5_minutes', clone $toDate, clone $toDate),
            'last_15_minutes' => $this->generateDateRange('last_15_minutes', clone $toDate, clone $toDate),
            'last_30_minutes' => $this->generateDateRange('last_30_minutes', clone $toDate, clone $toDate),
            'last_1_hour' => $this->generateDateRange('last_1_hour', clone $toDate, clone $toDate),
            'last_3_hours' => $this->generateDateRange('last_3_hours', clone $toDate, clone $toDate),
            'last_6_hours' => $this->generateDateRange('last_6_hours', clone $toDate, clone $toDate),
            'last_12_hours' => $this->generateDateRange('last_12_hours', clone $toDate, clone $toDate),
        
        ];
        
        return $presets;
    }


  
    public function render()
    {
        $rp = $this->report;
        $currentParams = $this->currentParamsReport();
        $presets = $this->createPresets(); 
        $timezoneData = DateReport::getTimeZones();

        $currentParams = $this->formatFromAndToDate($currentParams, 'Y-m-d H:i:s');

        return view('components.reports2.report-absolute-time-range', 
        [
            'rp' => $rp,
            'entityType' => $rp->entity_type,
            'reportType2' => $this->reportType2,
            'routeFilter' => route('report_filters' . '.update', $rp->id),
            'fromDate' => $currentParams['from_date'],
            'toDate' => $currentParams['to_date'],
            'presets' => $presets,
            'presetTitle' => $currentParams['preset_title'],
            'timezoneData' => $timezoneData,
            'timeZone' => $currentParams['time_zone'],
            'dateDisplayFormat' => $currentParams['date_display_format'] ?? 'y-m-d H:i:s',
        ]
    );
    }
}
