<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
            @php
                $ghgrp_1 = $tableDataSource['dataWidgets']['ghgrp_basin_production_and_emissions_all_year'];
                $ghgrp_2 = $tableDataSource['dataWidgets']['ghgrp_basin_production_and_emissions_by_months'];
            @endphp
            <div class="flex ">
                <div class="">
                    <x-renderer.report.chart-bar3v3 :dataSource="$ghgrp_1"/>
                    <p class='text-blue-600 font-bold text-lg pt-2 pl-80'>The entire previous year - {{$year-1}}</p>
                </div>
                <div class="">
                    <x-renderer.report.chart-bar3v3 :dataSource="$ghgrp_2"/>
                    <p class='text-blue-600 font-bold text-lg  pt-2 pl-28'>The selected period ({{$text}})</p>
                </div>
            </div>
    </div>
</div>