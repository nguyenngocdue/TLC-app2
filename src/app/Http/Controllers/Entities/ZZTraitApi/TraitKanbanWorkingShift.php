<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait TraitKanbanWorkingShift
{
	private function calculateShiftDurationByUser($start_at0, $end_at0, $uid)
	{
		$user = User::query()
			->with(["getWorkplace" => function ($query) {
				$query->with('getPublicHolidays');
			}])
			->find($uid);

		$workplace = $user->getWorkplace;
		$weekendDays = explode(",", $workplace->weekend_days);

		$standard_start_time = $workplace->standard_start_time;
		$standard_start_break = $workplace->standard_start_break;
		$break_duration_in_min = $workplace->break_duration_in_min;
		$standard_working_min = $workplace->standard_working_min;

		$morning_shift_in_min = Carbon::parse($standard_start_break)->diffInMinutes($standard_start_time);
		$afternoon_shift_in_min = $standard_working_min - $break_duration_in_min - $morning_shift_in_min;

		// dump($standard_working_min, $break_duration_in_min, $morning_shift_in_min);
		// dump($morning_shift_in_min, $afternoon_shift_in_min);

		$afternoon_start = Carbon::parse($standard_start_break)->addMinute($break_duration_in_min);
		$afternoon_end = Carbon::parse($standard_start_break)->addMinute($afternoon_shift_in_min);

		$shifts = [
			['start' => $standard_start_time, 'end' => $standard_start_break],
			['start' => $afternoon_start->format('H:i:s'), 'end' => $afternoon_end->format('H:i:s')],
			// Add other shifts as needed
		];

		$publicHolidays = $workplace->getPublicHolidays->pluck('ph_date')->toArray();

		// Log::info($shifts);
		// Log::info($weekendDays);
		// Log::info($workplace);
		// Log::info($publicHolidays);

		return $this->calculateShiftDuration($start_at0, $end_at0, $shifts, $weekendDays, $publicHolidays);
	}

	private function calculateShiftDuration($start_at0, $end_at0, $shifts, $weekendDays, $publicHolidays)
	{
		$start = Carbon::parse($start_at0);
		$end = Carbon::parse($end_at0);
		$duration = $end->diffInSeconds($start);
		$shiftSeconds = 0;

		while ($start->lessThan($end)) {
			// if ($start->isWeekend()) {
			// if (in_array($start->dayOfWeek, $weekendDays)) {
			if (in_array($start->dayOfWeek, $weekendDays) || in_array($start->format('Y-m-d'), $publicHolidays)) {
				$start->nextWeekday();
				continue;
			}

			foreach ($shifts as $shift) {
				$shiftStart = Carbon::parse($start->format('Y-m-d') . ' ' . $shift['start']);
				$shiftEnd = Carbon::parse($start->format('Y-m-d') . ' ' . $shift['end']);

				if ($end->lessThan($shiftStart) || $start->greaterThan($shiftEnd)) {
					// No overlap with this shift
					continue;
				}

				$overlapStart = $start->max($shiftStart);
				$overlapEnd = $end->min($shiftEnd);

				$shiftSeconds += $overlapStart->diffInSeconds($overlapEnd);
			}

			$start->nextWeekday();
		}

		$nonShiftSeconds = $duration - $shiftSeconds;

		return [
			'shift_seconds' => $shiftSeconds,
			'non_shift_seconds' => $nonShiftSeconds,
		];
	}
}
