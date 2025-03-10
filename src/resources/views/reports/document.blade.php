@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$mode)
@section('content')

<div class="px-4">
    @include('components.reports.shared-parameter')
</div>
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="w-[1000px] min-h-[1360px] items-center bg-white box-border p-8">
            <x-print.header6 />
        </div>
        <div style='page-break-after:always!important' class="w-[1000px] min-h-[1360px] items-center bg-white box-border p-8">
            {{-- <x-renderer.table showNo={{true}} :dataHeader="$tableDataHeader" :columns="$tableColumns" :dataSource="$tableDataSource" rotate45Width={{$rotate45Width}} maxH="{{$maxH}}" tableTrueWidth={{$tableTrueWidth?1:0}} /> --}}
         <x-renderer.report.pivot-table
                    modeType="{{$modeType}}"  
                    showNo={{true}} 
                    :tableColumns="$tableColumns" 
                    :params="$params" 
                    :dataSource="$tableDataSource"
                    tableTrueWidth={{$tableTrueWidth?1:0}} 
                    />
         <x-renderer.report.pivot-table
                    modeType="{{$modeType}}"  
                    showNo={{true}} 
                    :tableColumns="$tableColumns" 
                    :params="$params" 
                    :dataSource="$tableDataSource"
                    tableTrueWidth={{$tableTrueWidth?1:0}} 
                    />
         <x-renderer.report.pivot-table
                    modeType="{{$modeType}}"  
                    showNo={{true}} 
                    :tableColumns="$tableColumns" 
                    :params="$params" 
                    :dataSource="$tableDataSource"
                    tableTrueWidth={{$tableTrueWidth?1:0}} 
                    />
        </div>
    </div>
</div>
@endsection
