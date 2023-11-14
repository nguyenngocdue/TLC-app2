{{-- @props(["a"]); --}}
@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')
{{-- PARAMETERS --}}
@php
$dataWidgets = $tableDataSource['dataWidgets'];
@endphp
{{-- @dump($tableDataSource) --}}

<div class="px-4">
    @include('components.reports.shared-parameter')
    @include('components.reports.show-layout2')
</div>
@php
        $layout = '';
        switch ($optionPrint) {
            case 'landscape':
            $layout = 'w-[1400px] min-h-[940px]';
            break;
            case 'portrait':
                $layout = 'w-[1000px] min-h-[1450px]';
                break;
            default:
                break;
        }
@endphp

{{-- @dd($entity) --}}
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='' class="w-[1400px] min-h-[990px] items-center bg-white box-border p-8">
            <div class="pt-5">
                <x-print.header6 />
            </div>
            @php
                $ghgrp_1 = $dataWidgets['ghgrp_basin_production_and_emissions_all_year'];
                //dd($ghgrp_1);
                $ghgrp_2 = $dataWidgets['ghgrp_basin_production_and_emissions_by_months'];
            @endphp
            <div class="grid grid-cols-12 gap-2">
                <div class=" col-span-12 flex">
                    <x-renderer.report.chart-bar3v3 :dataSource="$ghgrp_1"/>
                    <x-renderer.report.chart-bar3v3 :dataSource="$ghgrp_2"/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection