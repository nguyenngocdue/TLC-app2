<?php

namespace App\View\Components\Renderer\Custom;

use App\Models\Diginet_business_trip_line;
use Carbon\Carbon;
use Illuminate\View\Component;

class BusinessTravelLine extends Component
{
    function __construct() {}

    function render()
    {
        $lines = Diginet_business_trip_line::query()
            ->where('employeeid', 'TLCM00024') //cuong
            // ->where('employeeid', 'TLCM01034')
            // ->groupBy('tb_document_id')
            ->get()
            ->sort(function ($a, $b) {
                return $a->tb_date <=> $b->tb_date;
            });
        $data = $lines->toArray();

        $groups = [];
        $currentGroup = [];
        $previousDate = null;

        foreach ($data as $item) {
            $currentDate = Carbon::parse($item['tb_date']);

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

        echo "<div class='col-span-12'>";
        // Display grouped data
        foreach ($groups as $index => $group) {
            echo "Group " . ($index + 1) . ":\n";
            foreach ($group as $item) {
                echo "<li>";
                echo $item['tb_date'] . " - " . $item['tb_reason'] . "\n";
                echo "</li>";
            }
            echo "\n";
        }
        echo "</div>";

        return view('components.renderer.custom.business-travel-line');
    }
}
