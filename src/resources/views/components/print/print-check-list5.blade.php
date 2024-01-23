@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', '')
@section('content')
<x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/>
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='{{$layout}}' class="items-center bg-white box-border p-8 ">
                <x-print.print-page-toc :dataSource="$headerDataSource" :headerDataSource="$entityDataSource" type="{{$type}}"/>
        </div>
        <x-renderer.page-break />
        @php $count = count($tableDataSource) ?? 0; @endphp
        @foreach($tableDataSource as $key => $value)
            <x-print.print-check-sheet-page 
                layout="{{$layout}}" 
                page='{{$key+1}}'
                type="qaqc_insp_chklst_shts"
                nominatedListFn="{{$nominatedListFn}}"
                :headerDataSource="$headerDataSource[$key]" 

                :tableColumns="$tableColumns"
                :tableDataSource="$value"
                />
            @if(($key + 1) != $count)            
            <x-renderer.page-break />
            @endif
        @endforeach
    </div>
</div>
@endsection
