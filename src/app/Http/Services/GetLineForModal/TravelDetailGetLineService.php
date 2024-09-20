<?php

namespace App\Http\Services\GetLineForModal;

use App\Models\Diginet_business_trip_line;
use App\Models\Fin_expense_claim_travel_detail;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TravelDetailGetLineService
{
    private function clusterIntoGroups($lines)
    {
        $groups = [];
        $currentGroup = [];
        $previousDate = null;

        foreach ($lines as $item) {
            $currentDate = Carbon::parse($item->tb_date);

            if ($previousDate && $currentDate->diffInDays($previousDate) > 1) {
                // If dates are not consecutive, push the current group to groups and start a new group
                $groups[] = $currentGroup;
                $currentGroup = [];
            }

            $currentGroup[] = $item;
            $previousDate = $currentDate;
        }

        // Add the last group to the groups array
        if (!empty($currentGroup)) {
            $groups[] = $currentGroup;
        }
        return $groups;
    }

    public function getLines(Request $request)
    {
        // Log::info($request);
        $employeeId = $request->employee_id;
        // Log::info("Employee ID: " . $employeeId);
        $result = [];

        $user_id = User::query()
            ->where('employeeid', $employeeId)
            ->first()
            ->id;

        $claimedLineFingerPrints = Fin_expense_claim_travel_detail::query()
            ->where('user_id', $user_id)
            ->get()
            ->pluck('diginet_business_trip_line_finger_print');

        $lines = Diginet_business_trip_line::query()
            ->where('employeeid', $employeeId)
            ->whereNotIn('finger_print', $claimedLineFingerPrints)
            ->get()
            ->sort(function ($a, $b) {
                return $a->tb_date <=> $b->tb_date;
            });

        $groups = $this->clusterIntoGroups($lines);

        foreach ($groups as $index => $group) {
            $groupId = Str::slug("Unclaimed Business Trip #" . ($index + 1));
            $groupData = [];
            $minDate = "";
            $maxDate = "";
            foreach ($group as $item) {
                if ($minDate == "" || $item->tb_date < $minDate) $minDate = $item->tb_date;
                if ($maxDate == "" || $item->tb_date > $maxDate) $maxDate = $item->tb_date;

                $groupData[] = [
                    'id' => "travel_line_" . $item->id,
                    'text' => $item->tb_date . " - " . $item->tb_reason,
                    'parent' => $groupId,
                    'data' => [
                        'type' => 'travel_line',
                        'diginet_business_trip_line_finger_print' => $item->finger_print,
                        'travel_date' => $item->tb_date,
                        'day_count' => $item->number_of_tb_day,
                        'travel_reason' => $item->tb_reason,
                        'employee_id' => $item->employeeid,
                        'user_id' => $user_id,
                    ],
                ];
            }
            $result[] = [
                'id' => $groupId,
                'parent' => '#',
                'text' => "Unclaimed Business Trip (" . $minDate . " to " . $maxDate . ")",
                'state' => ['opened' => true],
                'data' => ['type' => 'travel_group'],
            ];
            $result = array_merge($result, $groupData);
        }
        return $result;
    }
}
