<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
            @php
                $ghgrp_1 = $tableDataSource['dataWidgets']['ghgrp_basin_production_and_emissions_all_year'];
                $ghgrp_2 = $tableDataSource['dataWidgets']['ghgrp_basin_production_and_emissions_by_months'];
            @endphp
            <div class="grid grid-cols-12 gap-2">
                <div class=" col-span-6 flex flex-col">
                    <x-renderer.report.chart-bar3v3 :dataSource="$ghgrp_1"/>
                    <p class='text-blue-600 font-bold text-lg pt-2 pl-80'>Based on the data throughout the year {{$year-1}}</p>
                </div>
                <div class=" col-span-6 flex flex-col justify-self-start">
                    <x-renderer.report.chart-bar3v3 :dataSource="$ghgrp_2"/>
                    <p class='text-blue-600 font-bold text-lg  pt-2 pl-28'>Using data up to the chosen time period ({{$text}})</p>
                </div>
            </div>
    </div>
</div>