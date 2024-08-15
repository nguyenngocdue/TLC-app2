<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_report;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Dropdown9 extends Component
{
    //for report 2
    public function __construct(
        private $filterLinkDetails = [],
        private $name = 'No name',
        private $currentParams = [],
        private $allowClear = false,
        private $routeName = '',


        private $entityType = '',
        private $entityType2 = '',
        private $reportId = '',

    ) {}

    private function createLinkReports($rpLinks, $currentParams)
    {
        $result = [];
        foreach ($rpLinks as $rpLink) {
            if (!Route::has('rp_reports.show')) {
                dd('rp_reports.show is not defined');
            }
            $route = route('rp_reports.show', $rpLink->id);
    
            if (!$route) {
                dd('Route for rp_reports.show with ID ' . $rpLink->id . ' is not defined');
            }
    
            if ($currentParams) {
                $storedFilterKey = $rpLink ->stored_filter_key;
                $urlQuery = $this->buildUrl($currentParams);
                $route .= '?' . $urlQuery.'&stored_filter_key='.$storedFilterKey.'&report_id='.$rpLink->id;
            }
            $result[$rpLink->id] = $route;
        }
        return $result;
    }
    
    private function buildUrl($params)
    {
        $query = [];
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subValue) {
                    $query[] = urlencode($key) . '[]=' . urlencode($subValue);
                }
            } else {
                $query[] = urlencode($key) . '=' . urlencode($value);
            }
        }
        return implode('&', $query);
    }
    

    public function render()
    {
        $currentParams = $this->currentParams;
        $filterLinkDetails = (object)$this->filterLinkDetails;

        $rpLinks = $filterLinkDetails->map(function ($item) {
            $rpLinkId = $item->getFilterLink->linked_to_report_id;
            $rpLink = Rp_report::find($rpLinkId);
            return $rpLink;
        });

        $linkReports = $this->createLinkReports($rpLinks,$currentParams);
        return view('components.reports2.dropdown9', [
            'rpLinks' =>  $rpLinks,
            'name' => $this->name,
            'currentParams' => $currentParams,
            'allowClear' => $this->allowClear,
            'routeName' => $this->routeName,
            'entityType' => $this->entityType,
            'entityType2' => $this->entityType2,
            'reportId' => $this->reportId,
            'linkReports' => $linkReports,

        ]);
    }
}
