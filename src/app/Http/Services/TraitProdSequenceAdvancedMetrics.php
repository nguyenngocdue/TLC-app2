<?php

namespace App\Http\Services;

use App\Models\Public_holiday;
use Carbon\Carbon;

trait TraitProdSequenceAdvancedMetrics
{
    private function getTotalDaysHaveTimesheet($allProdSequences)
    {
        $result = [];
        foreach ($allProdSequences as $prodSequence) {
            $runs = $prodSequence->getProdRuns;
            foreach ($runs as $run) {
                $result[] = $run->date;
            }
        }
        $result = array_unique($result);
        return count($result);
    }

    private function getAllSundays($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate); // Replace with your start date
        $endDate = Carbon::parse($endDate);   // Replace with your end date

        $numberOfSundays = $startDate->diffInDaysFiltered(function (Carbon $date) {
            return $date->dayOfWeek === Carbon::SUNDAY;
        }, $endDate);

        return $numberOfSundays;
    }

    private function getAllPhDays($startDate, $endDate)
    {
        $publicHolidays = Public_holiday::query()
            ->where('workplace_id', 2) //<< TF1: 2
            ->get()
            ->pluck('ph_date')
            ->toArray();

        $startDate = Carbon::parse($startDate); // Replace with your start date
        $endDate = Carbon::parse($endDate);   // Replace with your end date
        $publicHolidaysWithinRange = 0;

        foreach ($publicHolidays as $holiday) {
            $holidayDate = Carbon::parse($holiday);

            if ($holidayDate->between($startDate, $endDate)) {
                if ($holidayDate->dayOfWeek !== Carbon::SUNDAY) {
                    // Log::info($holidayDate);
                    $publicHolidaysWithinRange++;
                }
            }
        }

        return $publicHolidaysWithinRange;
    }

    function get6Metrics($prodSequences, $minStartedAt, $maxFinishedAt)
    {
        $total_days_have_ts = $this->getTotalDaysHaveTimesheet($prodSequences);
        $total_calendar_days = Carbon::parse($minStartedAt)->diffInDays($maxFinishedAt) + 1;
        $allSundays = $this->getAllSundays($minStartedAt, $maxFinishedAt);
        $allPhDays = $this->getAllPhDays($minStartedAt, $maxFinishedAt);
        $total_days_no_sun_no_ph =  $total_calendar_days - $allSundays - $allPhDays;
        $total_discrepancy_days =  $total_days_no_sun_no_ph - $total_days_have_ts;

        $dataUpdated = [
            'total_calendar_days' => $total_calendar_days,
            'no_of_sundays' => $allSundays,
            'no_of_ph_days' => $allPhDays,
            'total_days_no_sun_no_ph' => $total_days_no_sun_no_ph,
            'total_days_have_ts' => $total_days_have_ts,
            'total_discrepancy_days' => $total_discrepancy_days,
        ];

        return $dataUpdated;
    }
}
