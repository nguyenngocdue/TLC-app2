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
            default:
                throw new Exception('Invalid timeframe specified.');
        }
    
        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s'),
        ];
    }
    

    private function createProSets(){
        $currentParams = $this->currentParamsReport();
    
        $browserTime = $currentParams['browser_time'];
        $url = "http://worldtimeapi.org/api/timezone/" . $browserTime;
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        $utcOffset = $data['utc_offset'];
        $timeAsNumber = DateReport::convertOffsetToNumber($utcOffset);
    
        $toDate = new DateTime(); // Current date and time
        $fromDate = clone $toDate; // Clone to_date to modify for from_date
    
        // Adjust to_date and from_date by the timeAsNumber
        $toDate->modify("{$timeAsNumber} hours");
        $fromDate->modify("{$timeAsNumber} hours");
        
        return [
            'today' => $this->generateDateRange('today', $toDate, $fromDate),
            'yesterday' => $this->generateDateRange('yesterday', $toDate, $fromDate),
            'last_2_days' => $this->generateDateRange('last_2_days', $toDate, $fromDate),
            'last_week' => $this->generateDateRange('last_week', $toDate, $fromDate),
            'last_month' => $this->generateDateRange('last_month', $toDate, $fromDate),
            'last_year' => $this->generateDateRange('last_year', $toDate, $fromDate),
        ];
    }

    private function getTimeZone(){
        $url = "http://worldtimeapi.org/api/timezone";
        $response = @file_get_contents($url);  // @ suppresses warning; could use more robust error handling
        if ($response === FALSE) {
            return ['error' => 'Unable to retrieve data'];
        }
    
        $timezones = json_decode($response, true);
        $groupedTimeZones = [];
    
        foreach ($timezones as $timezone) {
            $continent = explode('/', $timezone)[0];
            
            // Grouping by continent
            $groupedTimeZones[$continent][] = $timezone;
        }
    
        return $groupedTimeZones;
    }

    

    public function render()
    {
        $rp = $this->report;
        $currentParams = $this->currentParamsReport();
        $proSets = $this->createProSets(); 
        $timezoneData = $this->getTimeZone();

        return view('components.reports2.report-absolute-time-range', 
        [
            'rp' => $rp,
            'entityType' => $rp->entity_type,
            'reportType2' => $this->reportType2,
            'routeFilter' => route('report_filters' . '.update', $rp->id),
            'fromDate' => $currentParams['from_date'] ?? null,
            'toDate' => $currentParams['to_date'] ?? null,
            'proSets' => $proSets,
            'proSetTitle' => $currentParams['pro_set_title'] ?? 'Time Range',
            'timezoneData' => $timezoneData,
            'browserTime' => $currentParams['browser_time'] ?? 'Asia/Bangkok',
        ]
    );
    }
}
