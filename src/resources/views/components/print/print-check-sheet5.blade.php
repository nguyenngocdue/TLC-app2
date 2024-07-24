@extends('layouts.app')
@section('title', 'Print')
@section('topTitle', $topTitle)
@section('content')

<div class="block py-4 no-print" role="divider"></div>
{{-- <x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/> --}}

<div class="flex justify-center bg-only-print print-responsive">
    <div class="md:px-4 flex-grow flex-shrink-0 w-full overflow-x-auto">        
       <x-print.print-check-sheet-page
            {{-- layout="{{$layout}}"  --}}
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
