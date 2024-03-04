@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', '')
@section('content')
<x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/>

<div class="flex justify-center bg-only-print">
    <div class="md:px-4 flex-grow flex-shrink-0 w-full overflow-x-auto">        
       <x-print.print-check-sheet-page
            layout="{{$layout}}" 
            {{-- page=123 --}}
            type="{{$typePlural}}"
            nominatedListFn="{{$nominatedListFn}}"
            :headerDataSource="$headerDataSource" 

            :tableColumns="$tableColumns"
            :tableDataSource="$tableDataSource"
            />
    </div>
</div>
@endsection
