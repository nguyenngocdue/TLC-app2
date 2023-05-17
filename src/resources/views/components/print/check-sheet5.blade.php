@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', 'Show')
@section('content')
<x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/>
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div class="{{$layout}} items-center bg-white box-border px-8">
            <x-print.header5 :dataSource="$headerDataSource" />
            <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
        </div>
    </div>
</div>
@endsection
