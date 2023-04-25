@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', 'Show')
@section('content')

<div class="flex justify-center">
    <div class="md:px-4">
        <div class="w-[1000px] min-h-[1415px] items-center bor1der bg-white box-border px-8">
            <x-print.header5 :dataSource="$headerDataSource" />
            <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
        </div>
    </div>
</div>
@endsection
