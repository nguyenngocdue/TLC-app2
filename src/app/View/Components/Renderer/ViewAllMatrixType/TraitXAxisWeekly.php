<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait TraitXAxisWeekly
{
    protected $selectedYear = 2023;
    protected $selectedQuarter = 3;

    protected function getXAxis()
    {
        $xAxis = [];
        $start = [1, 4, 7, 10];
        $end = [3, 6, 9, 12];
        $endD = [31, 30, 30, 31];
        $quarterIndex = $this->selectedQuarter - 1;

        $startMonth = $start[$quarterIndex];
        $endMonth = $end[$quarterIndex];
        $endDate = $endD[$quarterIndex];

        $startDate = Carbon::create($this->selectedYear, $startMonth, 1)->startOfWeek(); // Start from January 1, 2023

        $endOfQuarter = Carbon::create($this->selectedYear, $endMonth, $endDate)->endOfWeek(); // End of March 2023

        $weekBeginningDates = [];

        while ($startDate->lte($endOfQuarter)) {
            if ($startDate->year == $this->selectedYear) {
                $weekBeginningDates[] = $startDate->copy(); // Copy the date to avoid modifying the original
            }
            $startDate->addWeek(); // Move to the next week
        }

        // Output the list of week beginning dates for the first quarter
        foreach ($weekBeginningDates as $date) {
            // echo $date->toDateString() . "\n";
            $item = [
                'dataIndex' => $date->toDateString(),
                'title' => "Week " . $date->week . "<br/>(" . $date->format("d/m") . ")",
                // 'tooltip' => "aa",
            ];
            $xAxis[] = $item;
        }

        return $xAxis;
    }
}
