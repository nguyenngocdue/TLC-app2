<?php

namespace App\View\Components\Modals\ParentId7;

use App\Models\Diginet_business_trip_line;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ParentId7TreeFinExpenseTravelLines extends ParentId7Tree
{
    protected $jsFileName = 'parentId7TreeFinExpenseTravelLines.js';

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

    function getDataSource()
    {
        $result = [];

        $lines = Diginet_business_trip_line::query()
            // ->where('employeeid', 'TLCM00024') //cuong
            ->where('employeeid', 'TLCM01034')
            // ->groupBy('tb_document_id')
            ->get()
            ->sort(function ($a, $b) {
                return $a->tb_date <=> $b->tb_date;
            });

        $groups = $this->clusterIntoGroups($lines);

        foreach ($groups as $index => $group) {
            $groupText = "Business Trip #" . ($index + 1);
            $groupData = [];
            foreach ($group as $item) {
                $groupData[] = [
                    'id' => "travel_line_" . $item->id,
                    'text' => $item->tb_date . " - " . $item->tb_reason,
                    'parent' => Str::slug($groupText),
                    'data' => [
                        'type' => 'travel_line',
                        'diginet_business_trip_line_finger_print' => $item->finger_print,
                        'travel_date' => $item->tb_date,
                        'day_count' => $item->number_of_tb_day,
                        'travel_reason' => $item->tb_reason,
                    ],
                ];
            }
            $result[] = [
                'id' => Str::slug($groupText),
                'parent' => '#',
                'text' => $groupText,
                'state' => ['opened' => true],
                'data' => ['type' => 'travel_group'],
            ];
            $result = array_merge($result, $groupData);
        }

        // Log::info($result);

        return $result;
    }
}
