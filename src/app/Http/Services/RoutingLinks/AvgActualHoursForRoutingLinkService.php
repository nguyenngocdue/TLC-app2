<?php

namespace App\Http\Services\RoutingLinks;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Prod_routing_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AvgActualHoursForRoutingLinkService
{
    function getEstimateErrorFactor($routing_details)
    {
        $errorFactor = [];
        foreach ($routing_details as $routing_detail) {
            switch (true) {
                    //User has not estimated yet
                case $routing_detail->target_hours == 0 && $routing_detail->avg_actual_hours > 0:
                    //Users has estimated but no actual work committed
                case $routing_detail->target_hours > 0 && $routing_detail->avg_actual_hours == 0:
                    //do nothing
                    break;
                    //User has estimated 
                case $routing_detail->target_hours > 0 && $routing_detail->avg_actual_hours > 0:
                    $errorFactor[] = $routing_detail->avg_actual_hours / $routing_detail->target_hours;
                    break;
            }
        }

        $mau = count($errorFactor);
        if ($mau == 0) return 1;
        // dump($errorFactor);
        return round(array_sum($errorFactor) / $mau, 3);
    }

    function handle(Request $request)
    {
        $routing_id = $request->input('routing_id');
        if (!$routing_id) {
            dump("routing_id is required for AvgActualHoursForRoutingLinkService.");
            return;
        }

        $routing_details = Prod_routing_detail::query()
            ->where('prod_routing_id', $routing_id)
            ->with(['getProdSequences' => function ($query) {
                $query->with(['getProdRunsSubCon']);
            }])
            ->get();

        $routingDetailWithNoSheetYet = [];
        $totalHourArray = [];
        $totalHourValue = [];
        $countOfKnownSequence = 0;
        $finishedArray = LibStatuses::$finishedArray;
        $naArray = LibStatuses::$naArray;

        foreach ($routing_details as $routing_detail) {
            $allSequences = $routing_detail->getProdSequences;
            $totalHours = 0;
            $count = 0;
            $countTrust = 0;
            $countOfNotNA = 0;
            foreach ($allSequences as $sequence) {
                if (!in_array($sequence->status, $naArray)) $countOfNotNA++;
                //Remove the sheet that has not been done
                if (!in_array($sequence->status, $finishedArray)) continue;
                $countTrust++;
                //Remove the sheet that involves sub-contract (is_rework = 10)
                if (count($sequence->getProdRunsSubCon) > 0) continue;
                //It is OK to accumulate the total hours
                $totalHours += $sequence->total_hours;
                $count++;
            }
            // $name = $routing_detail->getProdRoutingLink->name;
            if ($count > 0) {
                $totalHourArray[] = [
                    'obj' => $routing_detail,
                    'value' => round($totalHours / $count, 3),
                    'count' => 100 * round($countTrust / $countOfNotNA, 3),
                ];
                $totalHourValue[] = round($totalHours / $count, 3);
                $countOfKnownSequence++;

                // $routing_detail->avg_actual_hours = round($totalHours / $count, 3);
                // $routing_detail->save();
                // dump($name . " - " . $totalHours . " - " . round($totalHours / $count, 1));
            } else {
                $routingDetailWithNoSheetYet[] = $routing_detail;
                // dump($name . " - " . $totalHours . " - ANOTHER WAY");
            }
        }


        // dump($totalHourArray);
        // dump($avg_of_known_sequence);

        // dump(array_sum($totalHourValue));
        // dump($avg_of_known_sequence * count($routingDetailWithNoSheetYet));
        // dump($totalHoursToMakeAnProdOrder);

        foreach ($totalHourArray as $obj) {
            $routing_detail = $obj['obj'];
            $routing_detail->avg_actual_hours = $obj['value'];
            $routing_detail->trustability = $obj['count']; //round(100 * $obj['value'] / $totalHoursToMakeAnProdOrder, 3);
            $routing_detail->save();
        }

        // dump($routingDetailWithNoSheetYet[0]);
        foreach ($routingDetailWithNoSheetYet as $routing_detail) {
            $routing_detail->avg_actual_hours = 0; //$avg_of_known_sequence;
            $routing_detail->trustability = 0; //round(100 * $avg_of_known_sequence / $totalHoursToMakeAnProdOrder, 3);
            $routing_detail->save();
        }

        $estimationErrorFactor = $this->getEstimateErrorFactor($routing_details);
        dump("Error Factor: " . $estimationErrorFactor);

        $autoEstimatedHours = [];
        foreach ($routingDetailWithNoSheetYet as $routing_detail) {
            $value =  round($routing_detail->target_hours * $estimationErrorFactor, 3);
            $autoEstimatedHours[] = $value;
            $routing_detail->avg_actual_hours = $value;
            $routing_detail->save();
        }

        //Total hours of known + estimated hours but no sheets yet
        $totalHoursToMakeAnProdOrder = array_sum($totalHourValue) + array_sum($autoEstimatedHours);
        $totalCount = $countOfKnownSequence + count($autoEstimatedHours);

        $avg_of_known_sequence = round($totalHoursToMakeAnProdOrder / $totalCount, 3);
        dump("Avg of known sequence: (hours) " . $avg_of_known_sequence);

        foreach ($routingDetailWithNoSheetYet as $routing_detail) {
            if ($routing_detail->avg_actual_hours == 0) {
                $routing_detail->avg_actual_hours = $avg_of_known_sequence;
                $routing_detail->save();
            }
        }
    }
}
