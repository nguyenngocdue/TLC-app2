@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', 'Sign Off')
@section('content')
<x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/>
@php
        switch ($valueOptionPrint) {
            case 'landscape':
            $layout = 'w-[1414px] min-h-[1000px]';
            break;
            case 'portrait':
            default:
                $layout = 'w-[1000px] min-h-[1405px]';
                break;
        }
@endphp
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
                <x-print.table-of-contents :dataSource="$headerDataSource" :headerDataSource="$entityDataSource" type="{{$type}}"/>
        </div>
        <x-renderer.page-break />
        @php $count = count($tableDataSource) ?? 0; @endphp
        @foreach($tableDataSource as $key => $value)
            <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
                <x-print.header5 :dataSource="$headerDataSource[$key]" page='{{$key+1}}' />
                <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$value" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
            </div>
            @if(($key + 1) != $count)
            <x-renderer.page-break />
            @endif
        @endforeach
    </div>
</div>
@endsection