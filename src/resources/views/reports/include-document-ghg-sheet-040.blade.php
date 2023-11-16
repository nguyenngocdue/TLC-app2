<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
            @php
                $ghgrp_1 = $tableDataSource['dataWidgets']['ghgrp_basin_production_and_emissions_all_year'];
                $ghgrp_2 = $tableDataSource['dataWidgets']['ghgrp_basin_production_and_emissions_by_months'];
            @endphp
            <div class="grid grid-cols-12 gap-2">
                <div class=" col-span-6 flex flex-col">
                    <x-renderer.heading level=4  xalign="center" class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Based on the data for the entire year  - {{$year}}.</x-renderer.heading>
                    <x-renderer.report.chart-bar3v3 :dataSource="$ghgrp_1"/>
                </div>
                <div class=" col-span-6 flex flex-col justify-self-start">
                    <x-renderer.heading level=4 class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Based on the data up to the selected time.</x-renderer.heading>
                    <x-renderer.report.chart-bar3v3 :dataSource="$ghgrp_2"/>
                </div>
            </div>
    </div>
</div>