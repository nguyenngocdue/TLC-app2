@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', 'Show')
@section('content')
<x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/>
@php
        switch ($valueOptionPrint) {
            case 'landscape':
            $layout = 'w-[1415px] min-h-[1080px]';
            break;
            case 'portrait':
            default:
                $layout = 'w-[1000px] min-h-[1360px]';
                break;
        }
@endphp
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div class="{{$layout}} items-center bg-white box-border px-8">
            <x-print.header5 :dataSource="$headerDataSource" />
            <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
        </div>
    </div>
</div>
@endsection
