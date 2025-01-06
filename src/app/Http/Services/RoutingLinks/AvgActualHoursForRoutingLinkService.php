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
                $totalHourArray[$routing_detail->id] = round($totalHours / $count, 3);
                $countOfKnownSequence++;

                $routing_detail->avg_actual_hours = round($totalHours / $count, 3);
                $routing_detail->save();
                // dump($name . " - " . $totalHours . " - " . round($totalHours / $count, 1));
            } else {
                $routingDetailWithNoSheetYet[] = $routing_detail;
                // dump($name . " - " . $totalHours . " - ANOTHER WAY");
            }
        }
        // dump($totalHourArray);
        $avg_of_known_sequence = array_sum($totalHourArray) / $countOfKnownSequence;
        // dump($avg_of_known_sequence);
        // dump($routingDetailWithNoSheetYet[0]);
        foreach ($routingDetailWithNoSheetYet as $routing_detail) {
            $routing_detail->avg_actual_hours = $avg_of_known_sequence;
            $routing_detail->save();
        }
    }
}
