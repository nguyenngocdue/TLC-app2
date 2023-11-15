<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
            @php
                $ghgrp_1 = $tableDataSource['dataWidgets']['ghgrp_basin_production_and_emissions_all_year'];
                //dd($ghgrp_1);
                $ghgrp_2 = $tableDataSource['dataWidgets']['ghgrp_basin_production_and_emissions_by_months'];
            @endphp
            <div class="grid grid-cols-12 gap-2">
                <div class=" col-span-12 flex">
                    <x-renderer.report.chart-bar3v3 :dataSource="$ghgrp_1"/>
                    <x-renderer.report.chart-bar3v3 :dataSource="$ghgrp_2"/>
                </div>
            </div>
    </div>
</div>