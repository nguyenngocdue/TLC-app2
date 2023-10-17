<?php

namespace App\Http\Services;

use App\Models\Prod_sequence;
use App\Models\Public_holiday;
use Carbon\Carbon;

class ProdSequenceToProdOrderService
{
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

    private function getProdSequence($id)
    {
        return Prod_sequence::query()
            ->with(['getProdOrder' => function ($query) {
                $query->with(['getProdSequences' => function ($query) {
                    $query->with(['getProdRuns']);
                }]);
            }])
            ->find($id);
    }

    public function update($id)
    {
        $prodOrder = $this->getProdSequence($id)->getProdOrder;
        $allProdSequences = $prodOrder->getProdSequences;
        $total_days_have_ts = $this->getTotalDaysHaveTimesheet($allProdSequences);

        $totalHours = $allProdSequences->pluck('total_hours')->sum();
        $totalManHours = $allProdSequences->pluck('total_man_hours')->sum();
        $allStartDates = $allProdSequences->pluck('start_date');
        $minStartedAt = $allStartDates->min();
        $allEndDates = $allProdSequences->pluck('end_date');
        $maxFinishedAt = $allEndDates->max();
        $isFinished = $allProdSequences->pluck('status')->every(function ($value) {
            return in_array($value, ['finished', 'not_applicable', 'cancelled']);
        });

        $total_calendar_days = Carbon::parse($minStartedAt)->diffInDays($maxFinishedAt);
        $allSundays = $this->getAllSundays($minStartedAt, $maxFinishedAt);
        $allPhDays = $this->getAllPhDays($minStartedAt, $maxFinishedAt);
        $total_days_no_sun_no_ph =  $total_calendar_days - $allSundays - $allPhDays;
        $total_discrepancy_days =  $total_days_no_sun_no_ph - $total_days_have_ts;

        $dataUpdated = [
            'started_at' => $minStartedAt,
            'finished_at' => $maxFinishedAt,
            'total_hours' => $totalHours,
            'total_man_hours' => $totalManHours,

            'no_of_sundays' => $allSundays,
            'no_of_ph_days' => $allPhDays,
            'total_days_no_sun_no_ph' => $total_days_no_sun_no_ph,
            'total_days_have_ts' => $total_days_have_ts,
            'total_discrepancy_days' => $total_discrepancy_days,
        ];
        if ($isFinished) {
            $dataUpdated['status'] = 'finished';
        } else {
            $dataUpdated['status'] = 'in_progress';
        }
        $prodOrder->update($dataUpdated);
    }
}
