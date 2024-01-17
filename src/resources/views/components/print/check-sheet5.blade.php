@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', 'Sign-Off Sheet')
@section('content')
<x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/>
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style="{{$layout}}" class=" items-center bg-white box-border px-8">
            <x-print.header5 :dataSource="$headerDataSource"  type="{{$typePlural}}"/>
            <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
        </div>
    </div>
</div>
@endsection
