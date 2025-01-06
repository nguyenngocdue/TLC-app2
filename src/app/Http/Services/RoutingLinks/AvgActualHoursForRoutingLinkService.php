<?php

namespace App\Http\Services\RoutingLinks;

use App\Models\Prod_routing_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AvgActualHoursForRoutingLinkService
{
    function handle(Request $request)
    {
        $routing_id = $request->input('routing_id');
        if (!$routing_id) {
            dump("routing_id is required for AvgActualHoursForRoutingLinkService.");
            return;
        }

        $routing_details = Prod_routing_detail::query()
            ->where('prod_routing_id', $routing_id)
            ->with(['getProdSequences'])
            ->get();

        $routingDetailWithNoSheetYet = [];
        $totalHourArray = [];
        $totalHourValue = [];
        $countOfKnownSequence = 0;
        foreach ($routing_details as $routing_detail) {
            $allSequences = $routing_detail->getProdSequences;
            $totalHours = 0;
            $count = 0;
            foreach ($allSequences as $sequence) {
                //Remove the sheet that has not been done
                if ($sequence->status != 'finished') continue;
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
                    'value' => round($totalHours / $count, 3)
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
        $avg_of_known_sequence = round(array_sum($totalHourValue) / $countOfKnownSequence, 3);
        // dump($avg_of_known_sequence);

        //Total hours of known + unknown sequences to calculate weight
        $totalHoursToMakeAnProdOrder = array_sum($totalHourValue) + $avg_of_known_sequence * count($routingDetailWithNoSheetYet);
        dump(array_sum($totalHourValue));
        dump($avg_of_known_sequence * count($routingDetailWithNoSheetYet));
        dump($totalHoursToMakeAnProdOrder);

        foreach ($totalHourArray as $obj) {
            $routing_detail = $obj['obj'];
            $routing_detail->avg_actual_hours = $obj['value'];
            $routing_detail->actual_hours_weight = round(100 * $obj['value'] / $totalHoursToMakeAnProdOrder, 3);
            $routing_detail->save();
        }

        // dump($routingDetailWithNoSheetYet[0]);
        foreach ($routingDetailWithNoSheetYet as $routing_detail) {
            $routing_detail->avg_actual_hours = $avg_of_known_sequence;
            $routing_detail->actual_hours_weight = round(100 * $avg_of_known_sequence / $totalHoursToMakeAnProdOrder, 3);
            $routing_detail->save();
        }
    }
}
