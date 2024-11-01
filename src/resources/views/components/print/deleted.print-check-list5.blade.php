@extends('layouts.app')
@section('title', 'Print')
@section('topTitle', $topTitle)
@section('content')

<div class="block py-4 no-print" role="divider"></div>
{{-- <x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/> --}}

<div class="flex justify-center bg-only-print print-responsive ">
    <div class="md:px1-4 flex-grow flex-shrink-0 w-full overflow-x-auto">   

        <div class="w-90vw items-center bg-white box-border p-4vw mx-auto">
            <x-print.cover-page :dataSource="$headerDataSource" :headerDataSource="$entityDataSource" type="{{$type}}"/>
        </div>    
        <x-renderer.page-break /> 

        <div class="w-90vw items-center bg-white box-border p-4vw mx-auto">
            <x-print.print-page-toc :dataSource="$headerDataSource" :headerDataSource="$entityDataSource" type="{{$type}}"/>
        </div>
        <x-renderer.page-break />

        @php $count = count($tableDataSource) ?? 0; @endphp
        @foreach($tableDataSource as $key => $value)
            <x-print.print-check-sheet-page 
                {{-- layout="{{$layout}}"  --}}
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
