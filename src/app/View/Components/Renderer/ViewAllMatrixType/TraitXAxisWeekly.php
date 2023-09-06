<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait TraitXAxisWeekly
{
    protected function getXAxis()
    {
        $xAxis = [];

        $selectedYear =  date('Y', $this->viewportDate);

        $startDate = Carbon::create($selectedYear, 1, 1)->startOfWeek(); // Start from January 1, 2023
        $endOfQuarter = Carbon::create($selectedYear, 12, 31)->endOfWeek(); // End of March 2023

        $weekBeginningDates = [];

        while ($startDate->lte($endOfQuarter)) {
            if ($startDate->year == $selectedYear) {
                $weekBeginningDates[] = $startDate->copy(); // Copy the date to avoid modifying the original
            }
            $startDate->addWeek(); // Move to the next week
        }

        // Output the list of week beginning dates for the first quarter
        foreach ($weekBeginningDates as $date) {
            // echo $date->toDateString() . "\n";
            $item = [
                'dataIndex' => $date->toDateString(),
                'title' => "Week " . $date->week . " (" . $date->format("d/m/Y") . ")",
                'align' => 'center',
                'width' => 50,
            ];
            $xAxis[] = $item;
        }

        return $xAxis;
    }
}
